<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Organisation\Project;

use JsonException;
use Tracking3\Core\Client\AbstractRequest;
use Tracking3\Core\Client\EnvironmentHandlingService;
use Tracking3\Core\Client\Exception\Exception;
use Tracking3\Core\Client\Project\Project;
use Tracking3\Core\Client\Project\ProjectList;

class OrganisationProjectsRequest extends AbstractRequest
{
    /**
     * @param string $idOrganisation
     * @param bool $returnAsObject
     * @return array|Project[]|ProjectList
     * @throws Exception
     * @throws JsonException
     */
    public function getList(
        string $idOrganisation,
        bool $returnAsObject = true
    ) {
        $this->isUuidV4Valid($idOrganisation);

        $this->doAutoLogin();

        $uri = implode(
            '/',
            [
                EnvironmentHandlingService::buildBaseUri($this->configuration),
                'organisations',
                $idOrganisation,
                'projects',
            ]
        );

        $data = $this->getReal($uri);

        if ($returnAsObject) {
            return new ProjectList($data['payload']);
        }

        return $data['payload'];
    }


    /**
     * @param string $idOrganisation
     * @param string $idProject
     * @param bool $returnAsObject
     * @return array|Project
     * @throws Exception
     * @throws JsonException
     * @throws \Exception
     */
    public function get(
        string $idOrganisation,
        string $idProject,
        bool $returnAsObject = true
    ) {
        $this->isUuidV4Valid($idOrganisation);
//        $this->isUuidV4Valid($idProject);

        $this->doAutoLogin();

        $uri = implode(
            '/',
            [
                EnvironmentHandlingService::buildBaseUri($this->configuration),
                'organisations',
                $idOrganisation,
                'projects',
                $idProject,
            ]
        );

        $data = $this->getReal($uri);

        if ($returnAsObject) {
            return new Project($data['payload']);
        }

        return $data['payload'];

    }
}
