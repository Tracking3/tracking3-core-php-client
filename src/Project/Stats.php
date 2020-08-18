<?php

declare(strict_types=1);

namespace Tracking3\Core\Client\Project;

use DateTime;
use Exception;
use JsonSerializable;

class Stats implements JsonSerializable
{
    /**
     * @var null|int
     */
    protected $files;


    /**
     * @var null|int
     */
    protected $filesTrend;


    /**
     * @var null|array
     */
    protected $runtime;


    /**
     * @var null|DateTime
     */
    protected $runtimeEnd;


    /**
     * @var null|DateTime
     */
    protected $runtimeStart;


    /**
     * @var null|int
     */
    protected $size;


    /**
     * @var null|int
     */
    protected $sizeLimit;


    /**
     * @var null|int
     */
    protected $sizeTrend;


    /**
     * @var null|int
     */
    protected $users;


    /**
     * @var null|int
     */
    protected $usersTrend;


    /**
     * @param array $data
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->files = $data['files'] ?? null;
        $this->filesTrend = $data['filesTrend'] ?? null;
        $this->runtime = $data['runtime'] ?? null;
        $this->runtimeEnd = isset($data['runtimeEnd'])
            ? new DateTime($data['runtimeEnd'])
            : null;
        $this->runtimeStart = isset($data['runtimeStart'])
            ? new DateTime($data['runtimeStart'])
            : null;
        $this->size = $data['size'] ?? null;
        $this->sizeLimit = $data['sizeLimit'] ?? null;
        $this->sizeTrend = $data['sizeTrend'] ?? null;
        $this->users = $data['users'] ?? null;
        $this->usersTrend = $data['usersTrend'] ?? null;
    }


    /**
     * @return array
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function jsonSerialize()
    {
        $return = [
            'files' => $this->getFiles(),
            'filesTrend' => $this->getFilesTrend(),
            'runtime' => $this->getRuntime(),
            'runtimeEnd' => $this->hasRuntimeEnd()
                ? $this->getRuntimeEnd()->format(DateTime::ATOM)
                : null,
            'runtimeStart' => $this->hasRuntimeStart()
                ? $this->getRuntimeStart()->format(DateTime::ATOM)
                : null,
            'size' => $this->getSize(),
            'sizeLimit' => $this->getSizeLimit(),
            'sizeTrend' => $this->getSizeTrend(),
            'users' => $this->getUsers(),
            'usersTrend' => $this->getUsersTrend(),
        ];

        return array_filter(
            $return,
            static function ($v) {
                return !is_null($v);
            }
        );
    }


    /**
     * @return null|int
     */
    public function getFiles(): ?int
    {
        return $this->files;
    }


    /**
     * @return bool
     */
    public function hasFiles(): bool
    {
        return null !== $this->files;
    }


    /**
     * @param null|int $files
     * @return Stats
     */
    public function setFiles(?int $files): Stats
    {
        $this->files = $files;
        return $this;
    }


    /**
     * @return null|int
     */
    public function getFilesTrend(): ?int
    {
        return $this->filesTrend;
    }


    /**
     * @return bool
     */
    public function hasFilesTrend(): bool
    {
        return null !== $this->filesTrend;
    }


    /**
     * @param null|int $filesTrend
     * @return Stats
     */
    public function setFilesTrend(?int $filesTrend): Stats
    {
        $this->filesTrend = $filesTrend;
        return $this;
    }


    /**
     * @return null|array
     */
    public function getRuntime(): ?array
    {
        return $this->runtime;
    }


    /**
     * @return bool
     */
    public function hasRuntime(): bool
    {
        return null !== $this->runtime;
    }


    /**
     * @param null|array $runtime
     * @return Stats
     */
    public function setRuntime(?array $runtime): Stats
    {
        $this->runtime = $runtime;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasRuntimeEnd(): bool
    {
        return null !== $this->runtimeEnd;
    }


    /**
     * @return null|DateTime
     */
    public function getRuntimeEnd(): ?DateTime
    {
        return $this->runtimeEnd;
    }


    /**
     * @param null|DateTime $runtimeEnd
     * @return Stats
     */
    public function setRuntimeEnd(?DateTime $runtimeEnd): Stats
    {
        $this->runtimeEnd = $runtimeEnd;
        return $this;
    }


    /**
     * @return null|DateTime
     */
    public function getRuntimeStart(): ?DateTime
    {
        return $this->runtimeStart;
    }


    /**
     * @return bool
     */
    public function hasRuntimeStart(): bool
    {
        return null !== $this->runtimeStart;
    }


    /**
     * @param null|DateTime $runtimeStart
     * @return Stats
     */
    public function setRuntimeStart(?DateTime $runtimeStart): Stats
    {
        $this->runtimeStart = $runtimeStart;
        return $this;
    }


    /**
     * @return null|int
     */
    public function getSize(): ?int
    {
        return $this->size;
    }


    /**
     * @return bool
     */
    public function hasSize(): bool
    {
        return null !== $this->size;
    }


    /**
     * @param null|int $size
     * @return Stats
     */
    public function setSize(?int $size): Stats
    {
        $this->size = $size;
        return $this;
    }


    /**
     * @return null|int
     */
    public function getSizeLimit(): ?int
    {
        return $this->sizeLimit;
    }


    /**
     * @return bool
     */
    public function hasSizeLimit(): bool
    {
        return null !== $this->sizeLimit;
    }


    /**
     * @param null|int $sizeLimit
     * @return Stats
     */
    public function setSizeLimit(?int $sizeLimit): Stats
    {
        $this->sizeLimit = $sizeLimit;
        return $this;
    }


    /**
     * @return bool
     */
    public function hasSizeTrend(): bool
    {
        return null !== $this->sizeTrend;
    }


    /**
     * @return null|int
     */
    public function getSizeTrend(): ?int
    {
        return $this->sizeTrend;
    }


    /**
     * @param null|int $sizeTrend
     * @return Stats
     */
    public function setSizeTrend(?int $sizeTrend): Stats
    {
        $this->sizeTrend = $sizeTrend;
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
     * @return null|int
     */
    public function getUsers(): ?int
    {
        return $this->users;
    }


    /**
     * @param null|int $users
     * @return Stats
     */
    public function setUsers(?int $users): Stats
    {
        $this->users = $users;
        return $this;
    }


    /**
     * @return null|int
     */
    public function getUsersTrend(): ?int
    {
        return $this->usersTrend;
    }


    /**
     * @return bool
     */
    public function hasUsersTrend(): bool
    {
        return null !== $this->usersTrend;
    }


    /**
     * @param null|int $usersTrend
     * @return Stats
     */
    public function setUsersTrend(?int $usersTrend): Stats
    {
        $this->usersTrend = $usersTrend;
        return $this;
    }
}
