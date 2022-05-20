<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Http;

use finfo;
use JsonException;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Exception\Connection;
use Tracking3\Core\Client\Exception\Timeout;

class CurlRequestHandler implements RequestHandlerInterface
{

    public function __construct(
        private readonly HttpRequest $curl = new Curl()
    ) {
    }


    /**
     * @param string $httpMethod
     * @param string $uri
     * @param Configuration $configuration
     * @param null|array $requestBody
     * @param null|resource $file
     * @param null|array $customHeaders ['Header-Name' => 'header value']
     * @return array<string, string>
     * @throws JsonException
     */
    public function doRequest(
        string $httpMethod,
        string $uri,
        Configuration $configuration,
        array $requestBody = null,
        $file = null,
        ?array $customHeaders = []
    ): array {

        // default headers
        $headers = [];
        $headers['Accept'] = 'application/json';
        $headers['Authorization'] = $this->getAuthorizationHeaderValue($configuration);
        $headers['Content-Type'] = 'application/json';
        $headers['User-Agent'] = 'Tracking3 Core PHP Client ' . EnvironmentHandlingService::SELF_VERSION;
        $headers['X-Strip-Leading-Brackets'] = 'false';
        if (null !== $configuration->getIdApplication()) {
            $headers['X-Id-Application'] = $configuration->getIdApplication();
        }
        if (null !== $configuration->getIdApiTransaction()) {
            $headers['X-Id-Api-Transaction'] = $configuration->getIdApiTransaction();
        }

        // custom headers will overwrite default headers
        $headers = array_merge(
            $headers,
            $customHeaders
        );

        // handle file
        if ($file !== null) {
            $boundary = '---------------------' . md5(mt_rand() . microtime());
            $headers['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;
            $body = $this->generateMultipartBody(
                $requestBody,
                $file,
                $boundary
            );
            $this->getCurl()
                ->setOption(
                    CURLOPT_POST,
                    true
                );
            $this->getCurl()
                ->setOption(
                    CURLOPT_POSTFIELDS,
                    $body
                );
        } elseif (!empty($requestBody)) {
            $this->getCurl()
                ->setOption(
                    CURLOPT_POSTFIELDS,
                    json_encode(
                        $requestBody,
                        JSON_THROW_ON_ERROR
                    )
                );
        }

        // set curl options
        $this->getCurl()
            ->setOption(
                CURLOPT_CUSTOMREQUEST,
                $httpMethod
            );
        $this->getCurl()
            ->setOption(
                CURLOPT_HTTPHEADER,
                $this->convertHeaders($headers)
            );
        $this->getCurl()
            ->setOption(
                CURLOPT_RETURNTRANSFER,
                true
            );
        $this->getCurl()
            ->setOption(
                CURLOPT_TIMEOUT,
                $configuration->getTimeout()
            );
        $this->getCurl()
            ->setOption(
                CURLOPT_URL,
                $uri
            );

        // do request
        $response = $this->getCurl()
            ->execute();
        $httpStatus = $this->getCurl()
            ->getInfo(CURLINFO_HTTP_CODE);
        $errorCode = $this->getCurl()
            ->getErrorCode();
        $error = $this->getCurl()
            ->getError();

        if ($errorCode === 28 && $httpStatus === 0) {
            throw new Timeout(
                sprintf(
                    'Request exceeded timeout of %d',
                    $configuration->getTimeout()
                ),
                null,
                1592833821
            );
        }

        $this->getCurl()
            ->close();

        if ($errorCode) {
            throw new Connection(
                $error,
                null,
                $errorCode
            );
        }

        return [
            'status' => $httpStatus,
            // strip leading brackets
            'body' => substr(
                $response,
                6
            ),
        ];
    }


    /**
     * @return HttpRequest
     */
    public function getCurl(): HttpRequest
    {

        return $this->curl;
    }


    /**
     * @param array $array
     * @param array $result
     * @param null $parentKey
     * @return string[]
     */
    public function flattenArrayRecursively(
        array $array,
        array &$result = [],
        $parentKey = null
    ): array {

        foreach ($array as $key => $value) {
            $itemKey = ($parentKey
                    ? $parentKey . '['
                    : '') . $key . ($parentKey
                    ? ']'
                    : '');
            if (is_array($value)) {
                $this->flattenArrayRecursively(
                    $value,
                    $result,
                    $itemKey
                );
            } else {
                $result[$itemKey] = $value;
            }
        }
        return $result;
    }


    /**
     * @param Configuration $configuration
     * @return string
     */
    private function getAuthorizationHeaderValue(
        Configuration $configuration
    ): string {

        if (null !== $configuration->getAccessToken()) {
            return 'Bearer ' . $configuration->getAccessToken();
        }

        if (null !== $configuration->getRefreshToken()) {
            return 'Bearer ' . $configuration->getRefreshToken();
        }

        return 'Basic ' . base64_encode($configuration->getEmail() . ':' . $configuration->getPassword());
    }


    /**
     * @param array $headers
     * @return array
     */
    private function convertHeaders(
        array $headers
    ): array {

        $return = [];

        foreach ($headers as $key => $value) {
            if (null !== $key || null !== $value) {
                $return[] = $key . ': ' . $value;
            }
        }

        return $return;
    }


    /**
     * @param array $requestBody
     * @param resource $file
     * @param string $boundary
     * @return string
     */
    private function generateMultipartBody(
        array $requestBody,
        $file,
        string $boundary
    ): string {

        $disallow = [
            "\0",
            "\"",
            "\r",
            "\n",
        ];
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $filePath = stream_get_meta_data($file)['uri'];
        $data = file_get_contents($filePath);
        $mimeType = $fileInfo->buffer($data);

        // build normal parameters
        $requestBody = $this->flattenArrayRecursively($requestBody);
        foreach ($requestBody as $key => $value) {
            $key = str_replace(
                $disallow,
                '_',
                $key
            );
            $body[] = implode(
                "\r\n",
                [
                    'Content-Disposition: form-data; name="' . $key . '"',
                    '',
                    filter_var($value),
                ]
            );
        }

        // build file parameter
        $splitFilePath = explode(
            DIRECTORY_SEPARATOR,
            $filePath
        );
        $filePath = end($splitFilePath);
        $filePath = str_replace(
            $disallow,
            '_',
            $filePath
        );
        $body[] = implode(
            "\r\n",
            [
                'Content-Disposition: form-data; name="file"; filename="' . $filePath . '"',
                'Content-Type: ' . $mimeType,
                '',
                $data,
            ]
        );

        // add boundary for each parameter
        array_walk(
            $body,
            static function (&$part) use
            (
                $boundary
            ) {

                $part = "--{$boundary}\r\n{$part}";
            }
        );

        // add final boundary
        $body[] = '--' . $boundary . '--';
        $body[] = '';

        return implode(
            "\r\n",
            $body
        );
    }
}
