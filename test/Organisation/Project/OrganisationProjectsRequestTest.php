<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Organisation\Project;

use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Http\Http;
use Tracking3\Core\Client\Organisation\Project\OrganisationProjectsRequest;
use Tracking3\Core\Client\Project\Project;
use Tracking3\Core\Client\Project\ProjectList;
use Tracking3\Core\ClientTest\ConfigurationTrait;

class OrganisationProjectsRequestTest extends TestCase
{

    use ConfigurationTrait;

    public const ID_ORGANISATION = 'my-organisation-uuid';
    public const ID_PROJECT = 'my-project-uuid';


    /**
     * @throws JsonException
     */
    public function testOrganisationsProjectsGetList(): void
    {
        $configuration = $this->getConfiguration();

        /** @var OrganisationProjectsRequest|MockObject $requestMock */
        $requestMock = $this->getMockBuilder(OrganisationProjectsRequest::class)
            ->setConstructorArgs(
                [
                    $configuration,
                ]
            )
            ->setMethodsExcept(['getList'])
            ->getMock();

        $httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $httpMock->method('get')
            ->with(
                implode(
                    '/',
                    [
                        EnvironmentHandlingService::API_URI_ENV_PRODUCTION,
                        EnvironmentHandlingService::API_VERSION,
                        'organisations',
                        self::ID_ORGANISATION,
                        'projects',
                    ]
                )
            )
            ->willReturn(
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
            );

        $requestMock->method('getHttp')
            ->willReturn($httpMock);

        $requestMock->expects(self::exactly(3))
            ->method('isUuidV4Valid')
            ->with(self::ID_ORGANISATION);

        // test $returnAsObject = null -> object
        $firstResult = $requestMock->getList(self::ID_ORGANISATION);

        self::assertInstanceOf(ProjectList::class, $firstResult);
        self::assertCount(2, $firstResult);

        // test $returnAsObject = true -> object
        $secondResult = $requestMock->getList(self::ID_ORGANISATION, true);
        self::assertInstanceOf(ProjectList::class, $secondResult);
        self::assertCount(2, $secondResult);

        // test $returnAsObject = false -> array
        $thirdResult = $requestMock->getList(self::ID_ORGANISATION, false);
        self::assertIsArray($thirdResult);
        self::assertCount(2, $thirdResult);
    }


    /**
     * @throws JsonException
     */
    public function testOrganisationsProjectsGet(): void
    {
        $configuration = $this->getConfiguration();

        /** @var OrganisationProjectsRequest|MockObject $requestMock */
        $requestMock = $this->getMockBuilder(OrganisationProjectsRequest::class)
            ->setConstructorArgs(
                [
                    $configuration,
                ]
            )
            ->setMethodsExcept(['get'])
            ->getMock();

        $httpMock = $this->getMockBuilder(Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $httpMock->method('get')
            ->with(
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
                )
            )
            ->willReturn(
                [
                    'payload' => [
                        // payload to object mapping is tested somewhere else
                    ],
                ],
            );

        $requestMock->method('getHttp')
            ->willReturn($httpMock);

        $requestMock->expects(self::exactly(3))
            ->method('isUuidV4Valid')
            ->with(self::ID_ORGANISATION);

        // test $returnAsObject = null -> object
        $firstResult = $requestMock->get(
            self::ID_ORGANISATION,
            self::ID_PROJECT,
        );

        self::assertInstanceOf(Project::class, $firstResult);

        // test $returnAsObject = true -> object
        $secondResult = $requestMock->get(
            self::ID_ORGANISATION,
            self::ID_PROJECT,
            true,
        );
        self::assertInstanceOf(Project::class, $secondResult);

        // test $returnAsObject = false -> array
        $thirdResult = $requestMock->get(
            self::ID_ORGANISATION,
            self::ID_PROJECT,
            false,
        );
        self::assertIsArray($thirdResult);
    }
}
