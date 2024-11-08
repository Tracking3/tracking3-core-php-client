<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Organisation\Project;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Http\CurlRequestHandler;
use Tracking3\Core\Client\Organisation\Project\OrganisationProjectsRequest;
use Tracking3\Core\Client\Project\Project;
use Tracking3\Core\Client\Project\ProjectList;
use Tracking3\Core\ClientTest\ConfigurationTrait;
use Tracking3\Core\ClientTest\Http\CurlMock;

class OrganisationProjectsRequestTest extends TestCase
{

    use ConfigurationTrait;

    public const ID_ORGANISATION = '0cd8551b-ba33-4249-91b1-ee49c813671e';
    public const ID_PROJECT = 'a54d1e59-aa69-4021-98a2-38aeb3b75b25';


    /**
     * @throws JsonException
     */
    public function testOrganisationsProjectsGetList(): void
    {

        $configuration = $this->getConfiguration();

        $curlMock = new CurlMock();
        $curlMock->info = [
            CURLINFO_HTTP_CODE => 200,
        ];

        $curlMock->result = json_encode(
                [
                    'payload' => [
                        // payload to object mapping is tested somewhere else
                        [
                            'idProject' => 'foo',
                        ],
                        [
                            'idProject' => 'bar',
                        ],
                    ],
                ],
                JSON_THROW_ON_ERROR
            );

        $request = new OrganisationProjectsRequest(
            $configuration,
            new CurlRequestHandler($curlMock)
        );


        // test $returnAsObject = null -> object
        $firstResult = $request->getList(self::ID_ORGANISATION);

        self::assertInstanceOf(
            ProjectList::class,
            $firstResult
        );
        self::assertCount(
            2,
            $firstResult
        );


        // test $returnAsObject = true -> object
        $secondResult = $request->getList(
            self::ID_ORGANISATION,
            true
        );
        self::assertInstanceOf(
            ProjectList::class,
            $secondResult
        );
        self::assertCount(
            2,
            $secondResult
        );


        // test $returnAsObject = false -> array
        $thirdResult = $request->getList(
            self::ID_ORGANISATION,
            false
        );
        self::assertIsArray($thirdResult);
        self::assertCount(
            2,
            $thirdResult
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
                    'projects',
                ]
            ),
            $curlMock->getOption(CURLOPT_URL)
        );
    }


    /**
     * @throws JsonException
     */
    public function testOrganisationsProjectsGet(): void
    {

        $configuration = $this->getConfiguration();

        $curlMock = new CurlMock();
        $curlMock->info = [
            CURLINFO_HTTP_CODE => 200,
        ];

        $curlMock->result = json_encode(
                [
                    'payload' => [
                        // payload to object mapping is tested somewhere else
                    ],
                ],
                JSON_THROW_ON_ERROR
            );

        $request = new OrganisationProjectsRequest(
            $configuration,
            new CurlRequestHandler($curlMock)
        );


        // test $returnAsObject = null -> object
        $firstResult = $request->get(
            self::ID_ORGANISATION,
            self::ID_PROJECT,
        );

        self::assertInstanceOf(
            Project::class,
            $firstResult
        );

        // test $returnAsObject = true -> object
        $secondResult = $request->get(
            self::ID_ORGANISATION,
            self::ID_PROJECT,
            true,
        );
        self::assertInstanceOf(
            Project::class,
            $secondResult
        );

        // test $returnAsObject = false -> array
        $thirdResult = $request->get(
            self::ID_ORGANISATION,
            self::ID_PROJECT,
            false,
        );
        self::assertIsArray($thirdResult);


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
                    'projects',
                    self::ID_PROJECT,
                ]
            ),
            $curlMock->getOption(CURLOPT_URL)
        );
    }
}
