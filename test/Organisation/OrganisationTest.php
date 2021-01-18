<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Organisation;

use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Organisation\BillingAddress;
use Tracking3\Core\Client\Organisation\Organisation;

class OrganisationTest extends TestCase
{
    public const FIXTURE_EMAILS_INVOICE_STRING = 'john@example.com; jane@example.com';
    public const FIXTURE_EMAILS_INVOICE_ARRAY = [
        'john@example.com',
        'jane@example.com',
    ];


    public const FIXTURE_ID_ORGANISATION = 'some-uuid-version-four';


    public const FIXTURE_LABEL = 'my awesome organisation';


    public const FIXTURE_VAT_REG_NO = 'vatRegNo';


    public function testFullQualified(): void
    {
        $organisation = new Organisation(
            [
                'idOrganisation' => self::FIXTURE_ID_ORGANISATION,
                'label' => self::FIXTURE_LABEL,
                'billingAddress' => [
                    'firstName' => BillingAddressTest::FIXTURE_FIRST_NAME,
                    'lastName' => BillingAddressTest::FIXTURE_LAST_NAME,
                ],
                'vatRegNo' => self::FIXTURE_VAT_REG_NO,
                'emailsInvoice' => self::FIXTURE_EMAILS_INVOICE_STRING,
            ]
        );

        self::assertTrue($organisation->hasIdOrganisation());
        self::assertTrue($organisation->hasLabel());
        self::assertTrue($organisation->hasBillingAddress());
        self::assertTrue($organisation->hasVatRegNo());
        self::assertTrue($organisation->hasEmailsInvoice());

        self::assertEquals(self::FIXTURE_ID_ORGANISATION, $organisation->getIdOrganisation());
        self::assertEquals(self::FIXTURE_LABEL, $organisation->getLabel());
        self::assertInstanceOf(
            BillingAddress::class,
            $organisation->getBillingAddress()
        );
        self::assertEquals(
            self::FIXTURE_VAT_REG_NO,
            $organisation->getVatRegNo()
        );
        self::assertEquals(
            self::FIXTURE_EMAILS_INVOICE_ARRAY,
            $organisation->getEmailsInvoice()
        );
    }


    public function testEmailStripTailingColon()
    {
        $organisation = new Organisation(
            [
                'idOrganisation' => self::FIXTURE_ID_ORGANISATION,
                'label' => self::FIXTURE_LABEL,
                'emailsInvoice' => self::FIXTURE_EMAILS_INVOICE_STRING . '; ',
            ]
        );

        self::assertEquals(
            self::FIXTURE_EMAILS_INVOICE_ARRAY,
            $organisation->getEmailsInvoice()
        );
    }


    public function testNotExistingDataWillBeNull(): void
    {
        $organisation = new Organisation(
            [
                'idOrganisation' => self::FIXTURE_ID_ORGANISATION,
                'label' => self::FIXTURE_LABEL,
            ]
        );

        self::assertFalse($organisation->hasBillingAddress());
        self::assertFalse($organisation->hasVatRegNo());
        self::assertFalse($organisation->hasEmailsInvoice());

        self::assertNull($organisation->getBillingAddress());
        self::assertNull($organisation->getVatRegNo());
        self::assertNull($organisation->getEmailsInvoice());
    }


    /**
     * @throws JsonException
     */
    public function testJsonSerializableFullQualified(): void
    {
        $organisation = new Organisation(
            [
                'idOrganisation' => self::FIXTURE_ID_ORGANISATION,
                'label' => self::FIXTURE_LABEL,
                'billingAddress' => [
                    'firstName' => BillingAddressTest::FIXTURE_FIRST_NAME,
                    'lastName' => BillingAddressTest::FIXTURE_LAST_NAME,
                ],
                'vatRegNo' => self::FIXTURE_VAT_REG_NO,
                'emailsInvoice' => self::FIXTURE_EMAILS_INVOICE_STRING,
            ]
        );

        $array = json_decode(
            json_encode(
                $organisation,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEquals(self::FIXTURE_ID_ORGANISATION, $array['idOrganisation']);
        self::assertEquals(self::FIXTURE_LABEL, $array['label']);
        self::assertEquals(
            self::FIXTURE_VAT_REG_NO,
            $array['vatRegNo']
        );
        self::assertEquals(
            self::FIXTURE_EMAILS_INVOICE_ARRAY,
            $array['emailsInvoice']
        );
        self::assertNotNull($array['billingAddress']);
    }


    /**
     * @throws JsonException
     */
    public function testJsonSerializableNotExistingDataWillBeAbsent(): void
    {
        $organisation = new Organisation(
            [
                'idOrganisation' => self::FIXTURE_ID_ORGANISATION,
                'label' => self::FIXTURE_LABEL,
            ]
        );

        $array = json_decode(
            json_encode(
                $organisation,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEquals(self::FIXTURE_ID_ORGANISATION, $array['idOrganisation']);
        self::assertEquals(self::FIXTURE_LABEL, $array['label']);
        self::assertArrayNotHasKey('billingAddress', $array);
        self::assertArrayNotHasKey('vatRegNo', $array);
        self::assertArrayNotHasKey('emailsInvoice', $array);
    }
}
