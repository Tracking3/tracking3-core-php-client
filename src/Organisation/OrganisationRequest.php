<?php

namespace Tracking3\Core\Client\Organisation;

use JsonException;
use Tracking3\Core\Client\AbstractRequest;
use Tracking3\Core\Client\EnvironmentHandlingService;

class OrganisationRequest extends AbstractRequest
{
    /**
     * @param string $idOrganisation
     * @param bool $returnAsObject
     * @return array|Organisation
     * @throws JsonException
     */
    public function getOrganisation(
        string $idOrganisation,
        bool $returnAsObject = true
    ) {
        $uri = implode(
            '/',
            [
                EnvironmentHandlingService::buildBaseUri($this->configuration),
                'organisations',
                $idOrganisation,
            ]
        );

        $data = $this->getHttp()->get($uri);

        if ($returnAsObject) {
            return new Organisation($data['payload']);
        }

        return $data['payload'];
    }
}
