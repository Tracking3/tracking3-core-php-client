<?php

namespace Tracking3\Core\Client\Organisation;

use JsonSerializable;

class BillingAddress implements JsonSerializable
{

    /**
     * @var null|string
     */
    protected $firstName;


    /**
     * @var null|string
     */
    protected $lastName;


    /**
     * @var null|string
     */
    protected $street;


    /**
     * @var null|string
     */
    protected $number;


    /**
     * @var null|string
     */
    protected $city;


    /**
     * @var null|string
     */
    protected $code;


    /**
     * @var null|string
     */
    protected $country;


    public function __construct(?array $data)
    {
        $this->firstName = $data['firstName'] ?? null;
        $this->lastName = $data['lastName'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->number = $data['number'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->country = $data['country'] ?? null;
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $return = [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'street' => $this->street,
            'number' => $this->number,
            'city' => $this->city,
            'code' => $this->code,
            'country' => $this->country,
        ];

        return array_filter(
            $return,
            static function ($v) {
                return !is_null($v);
            }
        );
    }


    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    /**
     * @return bool
     */
    public function hasFirstName(): bool
    {
        return null !== $this->firstName;
    }


    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }


    /**
     * @return bool
     */
    public function hasLastName(): bool
    {
        return null !== $this->lastName;
    }


    /**
     * @return bool
     */
    public function hasStreet(): bool
    {
        return null !== $this->street;
    }


    /**
     * @return null|string
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }


    /**
     * @return null|string
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }


    /**
     * @return bool
     */
    public function hasNumber(): bool
    {
        return null !== $this->number;
    }


    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }


    /**
     * @return bool
     */
    public function hasCity(): bool
    {
        return null !== $this->city;
    }


    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }


    /**
     * @return bool
     */
    public function hasCode(): bool
    {
        return null !== $this->code;
    }


    /**
     * @return bool
     */
    public function hasCountry(): bool
    {
        return null !== $this->country;
    }


    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }
}
