<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Organisation;

use JsonSerializable;

class BillingAddress implements JsonSerializable
{

    protected null|string $firstName;


    protected null|string $lastName;


    protected null|string $street;


    protected null|string $number;


    protected null|string $city;


    protected null|string $postalCode;


    protected null|string $country;


    protected null|string $countryAlpha2;


    public function __construct(?array $data)
    {
        $this->firstName = $data['firstName'] ?? null;
        $this->lastName = $data['lastName'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->number = $data['number'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->postalCode = $data['postalCode'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->countryAlpha2 = $data['countryAlpha2'] ?? null;
    }


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {

        $return = [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'street' => $this->street,
            'number' => $this->number,
            'city' => $this->city,
            'postalCode' => $this->postalCode,
            'country' => $this->country,
            'countryAlpha2' => $this->countryAlpha2,
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
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }


    /**
     * @return bool
     */
    public function hasPostalCode(): bool
    {
        return null !== $this->postalCode;
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


    /**
     * @return bool
     */
    public function hasCountryAlpha2(): bool
    {
        return null !== $this->countryAlpha2;
    }


    /**
     * @return null|string
     */
    public function getCountryAlpha2(): ?string
    {
        return $this->countryAlpha2;
    }

}
