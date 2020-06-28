<?php

namespace Tracking3\Core\ClientTest;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\EnvironmentHandlingService;

class ConfigurationTest extends TestCase
{
    public const FIXTURE_API_VERSION = EnvironmentHandlingService::API_VERSION;


    public const FIXTURE_APPLICATION_ID = 'my-3rd-party-app-integration';


    public const FIXTURE_DO_AUTO_LOGIN = true;


    public const FIXTURE_EMAIL = 'john@example.com';


    public const FIXTURE_ENVIRONMENT = EnvironmentHandlingService::ENV_PRODUCTION;


    public const FIXTURE_PASSWORD = 's3cr37';


    public const FIXTURE_STRIP_LEADING_BRACKETS = true;


    public const FIXTURE_TIMEOUT = 60;


    public function testDefaultValues(): void
    {
        $configuration = new Configuration(
            [
                'password' => self::FIXTURE_PASSWORD,
                'email' => self::FIXTURE_EMAIL,
                'applicationId' => self::FIXTURE_APPLICATION_ID,
            ]
        );

        self::assertEquals(self::FIXTURE_PASSWORD, $configuration->getPassword());
        self::assertEquals(self::FIXTURE_EMAIL, $configuration->getEmail());
        self::assertEquals(self::FIXTURE_APPLICATION_ID, $configuration->getApplicationId());
        self::assertEquals(self::FIXTURE_DO_AUTO_LOGIN, $configuration->isDoAutoLogin());
        self::assertEquals(self::FIXTURE_TIMEOUT, $configuration->getTimeout());
        self::assertEquals(self::FIXTURE_API_VERSION, $configuration->getApiVersion());
        self::assertEquals(self::FIXTURE_ENVIRONMENT, $configuration->getEnvironment());
        self::assertEquals(self::FIXTURE_STRIP_LEADING_BRACKETS, $configuration->isStripLeadingBrackets());
    }


    public function testFullyQualifiedWithNoDefaultValues(): void
    {
        $configuration = new Configuration(
            [
                'password' => self::FIXTURE_PASSWORD,
                'email' => self::FIXTURE_EMAIL,
                'applicationId' => self::FIXTURE_APPLICATION_ID,
                'doAutoLogin' => false,
                'timeout' => self::FIXTURE_TIMEOUT + 1,
                'apiVersion' => 'v0.0.0',
                'environment' => EnvironmentHandlingService::ENV_DEVELOPMENT,
                'stripLeadingBrackets' => false,
            ]
        );

        self::assertEquals(self::FIXTURE_PASSWORD, $configuration->getPassword());
        self::assertEquals(self::FIXTURE_EMAIL, $configuration->getEmail());
        self::assertEquals(self::FIXTURE_APPLICATION_ID, $configuration->getApplicationId());
        self::assertEquals(false, $configuration->isDoAutoLogin());
        self::assertEquals(self::FIXTURE_TIMEOUT, $configuration->getTimeout());
        self::assertEquals('v0.0.0', $configuration->getApiVersion());
        self::assertEquals(EnvironmentHandlingService::ENV_DEVELOPMENT, $configuration->getEnvironment());
        self::assertEquals(false, $configuration->isStripLeadingBrackets());
    }


    public function testWrongEnvironmentWillFallbackToDefault(): void
    {

        $configuration = new Configuration(
            [
                'password' => self::FIXTURE_PASSWORD,
                'email' => self::FIXTURE_EMAIL,
                'environment' => 'unknown',
            ]
        );

        self::assertEquals(EnvironmentHandlingService::ENV_PRODUCTION, $configuration->getEnvironment());
    }


    public function testMissingPasswordWillThrowInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1592824383);
        new Configuration(
            [
                'email' => self::FIXTURE_EMAIL,
            ]
        );
    }


    public function testMissingEmailWillThrowInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(1592824491);
        new Configuration(
            [
                'password' => self::FIXTURE_PASSWORD,
            ]
        );
    }
}
