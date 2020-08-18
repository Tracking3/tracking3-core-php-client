<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Organisation;

use JsonException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Http\Http;
use Tracking3\Core\Client\Organisation\Organisation;
use Tracking3\Core\Client\Organisation\OrganisationRequest;
use Tracking3\Core\ClientTest\ConfigurationTrait;

class OrganisationRequestTest extends TestCase
{
    use ConfigurationTrait;

    protected const ID_ORGANISATION = 'organisation-uuid-version-four';
    protected const LABEL = 'organisation label';


    /**
     * @throws JsonException
     */
    public function testGetOrganisationToken(): void
    {
        $configuration = $this->getConfiguration();

        /** @var OrganisationRequest|MockObject $requestMock */
        $requestMock = $this->getMockBuilder(OrganisationRequest::class)
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
                    ]
                )
            )
            ->willReturn(
                [
                    'payload' => [
                        'idOrganisation' => self::ID_ORGANISATION,
                        'label' => self::LABEL,
                    ],
                ],
            );

        $requestMock->method('getHttp')
            ->willReturn($httpMock);

        $requestMock->expects(self::exactly(3))
            ->method('isUuidV4Valid')
            ->with(self::ID_ORGANISATION);

        // test $returnAsObject = null -> object
        $firstResult = $requestMock->get(self::ID_ORGANISATION);

        self::assertInstanceOf(Organisation::class, $firstResult);
        self::assertEquals(self::ID_ORGANISATION, $firstResult->getIdOrganisation());
        self::assertEquals(self::LABEL, $firstResult->getLabel());

        // test $returnAsObject = true -> object
        $secondResult = $requestMock->get(self::ID_ORGANISATION, true);
        self::assertInstanceOf(Organisation::class, $secondResult);
        self::assertEquals(self::ID_ORGANISATION, $secondResult->getIdOrganisation());
        self::assertEquals(self::LABEL, $secondResult->getLabel());

        // test $returnAsObject = false -> array
        $thirdResult = $requestMock->get(self::ID_ORGANISATION, false);
        self::assertIsArray($thirdResult);
        self::assertArrayHasKey('idOrganisation', $thirdResult);
        self::assertEquals(self::ID_ORGANISATION, $thirdResult['idOrganisation']);
        self::assertArrayHasKey('label', $thirdResult);
        self::assertEquals(self::LABEL, $thirdResult['label']);
    }
}
