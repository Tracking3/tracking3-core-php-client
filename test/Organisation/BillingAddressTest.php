<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Organisation;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Organisation\BillingAddress;

class BillingAddressTest extends TestCase
{
    public const FIXTURE_CITY = 'Example City';


    public const FIXTURE_COUNTRY = 'Germany';


    public const FIXTURE_COUNTRY_ALPHA2 = 'DE';


    public const FIXTURE_FIRST_NAME = 'John';


    public const FIXTURE_LAST_NAME = 'Doe';


    public const FIXTURE_NUMBER = '23';


    public const FIXTURE_POSTAL_CODE = '01234';


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
                'postalCode' => self::FIXTURE_POSTAL_CODE,
                'country' => self::FIXTURE_COUNTRY,
                'countryAlpha2' => self::FIXTURE_COUNTRY_ALPHA2,
            ]
        );

        self::assertTrue($billingAddress->hasFirstName());
        self::assertTrue($billingAddress->hasLastName());
        self::assertTrue($billingAddress->hasStreet());
        self::assertTrue($billingAddress->hasNumber());
        self::assertTrue($billingAddress->hasCity());
        self::assertTrue($billingAddress->hasPostalCode());
        self::assertTrue($billingAddress->hasCountry());
        self::assertTrue($billingAddress->hasCountryAlpha2());

        self::assertEquals(self::FIXTURE_FIRST_NAME, $billingAddress->getFirstName());
        self::assertEquals(self::FIXTURE_LAST_NAME, $billingAddress->getLastName());
        self::assertEquals(self::FIXTURE_STREET, $billingAddress->getStreet());
        self::assertEquals(self::FIXTURE_NUMBER, $billingAddress->getNumber());
        self::assertEquals(
            self::FIXTURE_CITY,
            $billingAddress->getCity()
        );
        self::assertEquals(
            self::FIXTURE_POSTAL_CODE,
            $billingAddress->getPostalCode()
        );
        self::assertEquals(
            self::FIXTURE_COUNTRY,
            $billingAddress->getCountry()
        );
        self::assertEquals(
            self::FIXTURE_COUNTRY_ALPHA2,
            $billingAddress->getCountryAlpha2()
        );
    }


    public function testNotExistingDataWillBeNull(): void
    {
        $billingAddress = new BillingAddress([]);

        self::assertFalse($billingAddress->hasFirstName());
        self::assertFalse($billingAddress->hasLastName());
        self::assertFalse($billingAddress->hasStreet());
        self::assertFalse($billingAddress->hasNumber());
        self::assertFalse($billingAddress->hasCity());
        self::assertFalse($billingAddress->hasPostalCode());
        self::assertFalse($billingAddress->hasCountry());
        self::assertFalse($billingAddress->hasCountryAlpha2());

        self::assertNull($billingAddress->getFirstName());
        self::assertNull($billingAddress->getLastName());
        self::assertNull($billingAddress->getStreet());
        self::assertNull($billingAddress->getNumber());
        self::assertNull($billingAddress->getCity());
        self::assertNull($billingAddress->getPostalCode());
        self::assertNull($billingAddress->getCountry());
        self::assertNull($billingAddress->getCountryAlpha2());
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
                'postalCode' => self::FIXTURE_POSTAL_CODE,
                'country' => self::FIXTURE_COUNTRY,
                'countryAlpha2' => self::FIXTURE_COUNTRY_ALPHA2,
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
        self::assertEquals(
            self::FIXTURE_CITY,
            $array['city']
        );
        self::assertEquals(
            self::FIXTURE_POSTAL_CODE,
            $array['postalCode']
        );
        self::assertEquals(
            self::FIXTURE_COUNTRY,
            $array['country']
        );
        self::assertEquals(
            self::FIXTURE_COUNTRY_ALPHA2,
            $array['countryAlpha2']
        );
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
