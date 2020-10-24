<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Organisation;

use JsonSerializable;

class Organisation implements JsonSerializable
{

    /**
     * @var string
     */
    protected $idOrganisation;


    /**
     * @var string
     */
    protected $label;


    /**
     * @var null|BillingAddress
     */
    protected $billingAddress;


    /**
     * @var null|string
     */
    protected $vatRegNo;


    /**
     * @var null|array
     */
    protected $emailsInvoice;


    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->idOrganisation = $data['idOrganisation'];
        $this->label = $data['label'];
        $this->billingAddress = empty($data['billingAddress'])
            ? null
            : new BillingAddress($data['billingAddress']);
        $this->vatRegNo = $data['vatRegNo'] ?? null;
        $this->emailsInvoice = isset($data['emailsInvoice'])
            // trim whitespaces
            ? array_map(
                'trim',
                preg_split(
                    '/[,;]/',
                    $data['emailsInvoice']
                )
            )
            : null;
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $return = [
            'idOrganisation' => $this->idOrganisation,
            'label' => $this->label,
            'billingAddress' => $this->billingAddress,
            'vatRegNo' => $this->vatRegNo,
            'emailsInvoice' => $this->emailsInvoice,
        ];

        return array_filter(
            $return,
            static function ($v) {
                return !is_null($v);
            }
        );
    }


    /**
     * @return bool
     */
    public function hasIdOrganisation(): bool
    {
        return null !== $this->idOrganisation;
    }


    /**
     * @return string
     */
    public function getIdOrganisation(): string
    {
        return $this->idOrganisation;
    }


    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }


    /**
     * @return bool
     */
    public function hasLabel(): bool
    {
        return null !== $this->label;
    }


    /**
     * @return null|BillingAddress
     */
    public function getBillingAddress(): ?BillingAddress
    {
        return $this->billingAddress;
    }


    /**
     * @return bool
     */
    public function hasBillingAddress(): bool
    {
        return null !== $this->billingAddress;
    }


    /**
     * @return bool
     */
    public function hasVatRegNo(): bool
    {
        return null !== $this->vatRegNo;
    }


    /**
     * @return null|string
     */
    public function getVatRegNo(): ?string
    {
        return $this->vatRegNo;
    }


    /**
     * @return null|string[]
     */
    public function getEmailsInvoice(): ?array
    {
        return $this->emailsInvoice;
    }


    /**
     * @return bool
     */
    public function hasEmailsInvoice(): bool
    {
        return !empty($this->emailsInvoice);
    }
}
