<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Organisation;

use JsonException;
use Tracking3\Core\Client\AbstractRequest;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Organisation\Project\OrganisationProjectsRequest;

class OrganisationRequest extends AbstractRequest
{
    /**
     * @param string $idOrganisation
     * @param bool $returnAsObject
     * @return array|Organisation
     * @throws JsonException
     */
    public function get(
        string $idOrganisation,
        bool $returnAsObject = true
    )
    {
        $this->isUuidV4Valid($idOrganisation);

        $this->doAutoLogin();

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


    /**
     * @return OrganisationProjectsRequest
     */
    public function projects(): OrganisationProjectsRequest
    {
        return new OrganisationProjectsRequest($this->configuration);
    }
}
