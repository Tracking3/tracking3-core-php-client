<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Project;

use DateTime;
use Exception;
use JsonSerializable;

class Project implements JsonSerializable
{

    /**
     * @var null|string
     */
    protected $idProject;


    /**
     * @var null|string
     */
    protected $idOwner;


    /**
     * @var null|string
     */
    protected $label;


    /**
     * @var null|bool
     */
    protected $active;


    /**
     * @var null|DateTime
     */
    protected $created;


    /**
     * @var null|float
     */
    protected $fps;


    /**
     * @var null|int
     */
    protected $resolutionHeight;


    /**
     * @var null|int
     */
    protected $resolutionWidth;


    /**
     * @var null|Stats
     */
    protected $stats;


    /**
     * @var null|string[]
     */
    protected $users;


    /**
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->idProject = $data['idProject'] ?? null;
        $this->idOwner = $data['idOwner'] ?? null;
        $this->label = $data['label'] ?? null;
        $this->active = $data['active'] ?? null;
        $this->created = isset($data['created'])
            ? new DateTime($data['created'])
            : null;
        $this->fps = $data['fps'] ?? null;
        $this->resolutionHeight = $data['resolutionHeight'] ?? null;
        $this->resolutionWidth = $data['resolutionWidth'] ?? null;
        $this->stats = isset($data['stats'])
            ? new Stats($data['stats'])
            : null;
        $this->users = $data['users'] ?? null;
    }


    /**
     * @return array
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function jsonSerialize()
    {
        $return = [
            'idProject' => $this->getIdProject(),
            'idOwner' => $this->getIdOwner(),
            'label' => $this->getLabel(),
            'active' => $this->isActive(),
            'created' => $this->hasCreated()
                ? $this->getCreated()->format(DateTime::ATOM)
                : null,
            'fps' => $this->getFps(),
            'resolutionHeight' => $this->getResolutionHeight(),
            'resolutionWidth' => $this->getResolutionWidth(),
            'stats' => $this->getStats(),
            'users' => $this->getUsers(),
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
    public function getIdProject(): ?string
    {
        return $this->idProject;
    }


    /**
     * @return bool
     */
    public function hasIdProject(): bool
    {
        return null !== $this->idProject;
    }


    /**
     * @param null|string $idProject
     * @return Project
     */
    public function setIdProject(?string $idProject): Project
    {
        $this->idProject = $idProject;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getIdOwner(): ?string
    {
        return $this->idOwner;
    }


    /**
     * @return bool
     */
    public function hasIdOwner(): bool
    {
        return null !== $this->idOwner;
    }


    /**
     * @param null|string $idOwner
     * @return Project
     */
    public function setIdOwner(?string $idOwner): Project
    {
        $this->idOwner = $idOwner;
        return $this;
    }


    /**
     * @return null|string
     */
    public function getLabel(): ?string
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
     * @param null|string $label
     * @return Project
     */
    public function setLabel(?string $label): Project
    {
        $this->label = $label;
        return $this;
    }


    /**
     * @return null|bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }


    /**
     * @return bool
     */
    public function hasActive(): bool
    {
        return null !== $this->active;
    }


    /**
     * @param null|bool $active
     * @return Project
     */
    public function setActive(?bool $active): Project
    {
        $this->active = $active;
        return $this;
    }


    /**
     * @return null|DateTime
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }


    /**
     * @return bool
     */
    public function hasCreated(): bool
    {
        return null !== $this->created;
    }


    /**
     * @param null|DateTime $created
     * @return Project
     */
    public function setCreated(?DateTime $created): Project
    {
        $this->created = $created;
        return $this;
    }


    /**
     * @return null|float
     */
    public function getFps(): ?float
    {
        return $this->fps;
    }


    /**
     * @return bool
     */
    public function hasFps(): bool
    {
        return null !== $this->fps;
    }


    /**
     * @param null|float $fps
     * @return Project
     */
    public function setFps(?float $fps): Project
    {
        $this->fps = $fps;
        return $this;
    }


    /**
     * @return null|int
     */
    public function getResolutionHeight(): ?int
    {
        return $this->resolutionHeight;
    }


    /**
     * @return bool
     */
    public function hasResolutionHeight(): bool
    {
        return null !== $this->resolutionHeight;
    }


    /**
     * @param null|int $resolutionHeight
     * @return Project
     */
    public function setResolutionHeight(?int $resolutionHeight): Project
    {
        $this->resolutionHeight = $resolutionHeight;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasResolutionWidth(): bool
    {
        return null !== $this->resolutionWidth;
    }


    /**
     * @return null|int
     */
    public function getResolutionWidth(): ?int
    {
        return $this->resolutionWidth;
    }


    /**
     * @param null|int $resolutionWidth
     * @return Project
     */
    public function setResolutionWidth(?int $resolutionWidth): Project
    {
        $this->resolutionWidth = $resolutionWidth;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasStats(): bool
    {
        return null !== $this->stats;
    }


    /**
     * @return null|Stats
     */
    public function getStats(): ?Stats
    {
        return $this->stats;
    }


    /**
     * @param null|Stats $stats
     * @return Project
     */
    public function setStats(?Stats $stats): Project
    {
        $this->stats = $stats;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasUsers(): bool
    {
        return null !== $this->users;
    }


    /**
     * @return null|string[]
     */
    public function getUsers(): ?array
    {
        return $this->users;
    }


    /**
     * @param null|string[] $users
     * @return Project
     */
    public function setUsers(?array $users): Project
    {
        $this->users = $users;
        return $this;
    }
}
