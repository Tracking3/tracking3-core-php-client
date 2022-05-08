<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Organisation;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Organisation\Organisation;
use Tracking3\Core\Client\Organisation\OrganisationRequest;
use Tracking3\Core\ClientTest\ConfigurationTrait;
use Tracking3\Core\ClientTest\Http\CurlMock;

class OrganisationRequestTest extends TestCase
{
    use ConfigurationTrait;

    protected const ID_ORGANISATION = 'dc936ae6-26e4-4d3e-b5d2-134512ea7330';
    protected const LABEL = 'organisation label';


    /**
     * @throws JsonException
     */
    public function testGetOrganisationToken(): void
    {

        $curlMock = new CurlMock();
        $curlMock->info = [
            CURLINFO_HTTP_CODE => 200,
        ];

        $curlMock->result = ")]}',\n" . json_encode(
                [
                    'payload' => [
                        'idOrganisation' => self::ID_ORGANISATION,
                        'label' => self::LABEL,
                    ],
                ],
                JSON_THROW_ON_ERROR
            );

        $request = new OrganisationRequest(
            $this->getConfiguration(),
            new CurlRequestHandler($curlMock)
        );


        // test $returnAsObject = null -> object
        $firstResult = $request->get(self::ID_ORGANISATION);

        self::assertInstanceOf(
            Organisation::class,
            $firstResult
        );
        self::assertEquals(
            self::ID_ORGANISATION,
            $firstResult->getIdOrganisation()
        );
        self::assertEquals(
            self::LABEL,
            $firstResult->getLabel()
        );


        // test $returnAsObject = true -> object
        $secondResult = $request->get(
            self::ID_ORGANISATION,
            true
        );
        self::assertInstanceOf(
            Organisation::class,
            $secondResult
        );
        self::assertEquals(
            self::ID_ORGANISATION,
            $secondResult->getIdOrganisation()
        );
        self::assertEquals(
            self::LABEL,
            $secondResult->getLabel()
        );


        // test $returnAsObject = false -> array
        $thirdResult = $request->get(
            self::ID_ORGANISATION,
            false
        );
        self::assertIsArray($thirdResult);
        self::assertArrayHasKey(
            'idOrganisation',
            $thirdResult
        );
        self::assertEquals(
            self::ID_ORGANISATION,
            $thirdResult['idOrganisation']
        );
        self::assertArrayHasKey(
            'label',
            $thirdResult
        );
        self::assertEquals(
            self::LABEL,
            $thirdResult['label']
        );


        // test executed
        self::assertEquals(
            3,
            $curlMock->executed
        );


        // test url
        self::assertEquals(
            implode(
                '/',
                [
                    EnvironmentHandlingService::API_URI_ENV_PRODUCTION,
                    EnvironmentHandlingService::API_VERSION,
                    'organisations',
                    self::ID_ORGANISATION,
                ]
            ),
            $curlMock->getOption(CURLOPT_URL)
        );

    }
}
