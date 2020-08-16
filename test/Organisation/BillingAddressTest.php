<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Organisation;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Organisation\BillingAddress;

class BillingAddressTest extends TestCase
{
    public const FIXTURE_CITY = 'Example City';


    public const FIXTURE_CODE = '01234';


    public const FIXTURE_COUNTRY = 'Germany';


    public const FIXTURE_FIRST_NAME = 'John';


    public const FIXTURE_LAST_NAME = 'Doe';


    public const FIXTURE_NUMBER = '23';


    public const FIXTURE_STREET = 'High Way';


    public function testFullQualified(): void
    {
        $billingAddress = new BillingAddress(
            [
                'firstName' => self::FIXTURE_FIRST_NAME,
                'lastName' => self::FIXTURE_LAST_NAME,
                'street' => self::FIXTURE_STREET,
                'number' => self::FIXTURE_NUMBER,
                'city' => self::FIXTURE_CITY,
                'code' => self::FIXTURE_CODE,
                'country' => self::FIXTURE_COUNTRY,
            ]
        );

        self::assertTrue($billingAddress->hasFirstName());
        self::assertTrue($billingAddress->hasLastName());
        self::assertTrue($billingAddress->hasStreet());
        self::assertTrue($billingAddress->hasNumber());
        self::assertTrue($billingAddress->hasCity());
        self::assertTrue($billingAddress->hasCode());
        self::assertTrue($billingAddress->hasCountry());

        self::assertEquals(self::FIXTURE_FIRST_NAME, $billingAddress->getFirstName());
        self::assertEquals(self::FIXTURE_LAST_NAME, $billingAddress->getLastName());
        self::assertEquals(self::FIXTURE_STREET, $billingAddress->getStreet());
        self::assertEquals(self::FIXTURE_NUMBER, $billingAddress->getNumber());
        self::assertEquals(self::FIXTURE_CITY, $billingAddress->getCity());
        self::assertEquals(self::FIXTURE_CODE, $billingAddress->getCode());
        self::assertEquals(self::FIXTURE_COUNTRY, $billingAddress->getCountry());
    }


    public function testNotExistingDataWillBeNull(): void
    {
        $billingAddress = new BillingAddress([]);

        self::assertFalse($billingAddress->hasFirstName());
        self::assertFalse($billingAddress->hasLastName());
        self::assertFalse($billingAddress->hasStreet());
        self::assertFalse($billingAddress->hasNumber());
        self::assertFalse($billingAddress->hasCity());
        self::assertFalse($billingAddress->hasCode());
        self::assertFalse($billingAddress->hasCountry());

        self::assertNull($billingAddress->getFirstName());
        self::assertNull($billingAddress->getLastName());
        self::assertNull($billingAddress->getStreet());
        self::assertNull($billingAddress->getNumber());
        self::assertNull($billingAddress->getCity());
        self::assertNull($billingAddress->getCode());
        self::assertNull($billingAddress->getCountry());
    }


    /**
     * @throws JsonException
     */
    public function testJsonSerializableFullQualified(): void
    {
        $billingAddress = new BillingAddress(
            [
                'firstName' => self::FIXTURE_FIRST_NAME,
                'lastName' => self::FIXTURE_LAST_NAME,
                'street' => self::FIXTURE_STREET,
                'number' => self::FIXTURE_NUMBER,
                'city' => self::FIXTURE_CITY,
                'code' => self::FIXTURE_CODE,
                'country' => self::FIXTURE_COUNTRY,
            ]
        );


        $array = json_decode(
            json_encode(
                $billingAddress,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEquals(self::FIXTURE_FIRST_NAME, $array['firstName']);
        self::assertEquals(self::FIXTURE_LAST_NAME, $array['lastName']);
        self::assertEquals(self::FIXTURE_STREET, $array['street']);
        self::assertEquals(self::FIXTURE_NUMBER, $array['number']);
        self::assertEquals(self::FIXTURE_CITY, $array['city']);
        self::assertEquals(self::FIXTURE_CODE, $array['code']);
        self::assertEquals(self::FIXTURE_COUNTRY, $array['country']);
    }


    /**
     * @throws JsonException
     */
    public function testJsonSerializableNotExistingDataWillBeAbsent(): void
    {
        $billingAddress = new BillingAddress([]);

        $array = json_decode(
            json_encode(
                $billingAddress,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEmpty($array);
    }
}
