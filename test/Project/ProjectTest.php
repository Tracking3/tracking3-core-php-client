<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Project;

use DateTime;
use Exception;
use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Project\Project;
use Tracking3\Core\Client\Project\Stats;

class ProjectTest extends TestCase
{
    public const FIXTURE_ACTIVE = true;


    public const FIXTURE_CREATED = '2019-10-12T22:44:03+00:00';


    public const FIXTURE_FPS = 29.97;


    public const FIXTURE_ID_OWNER = 'my-owner-uuid';


    public const FIXTURE_ID_PROJECT = 'my-project-uuid';


    public const FIXTURE_LABEL = 'my project label';


    public const FIXTURE_RESOLUTION_HEIGHT = 1111;
    public const FIXTURE_RESOLUTION_WIDTH = 2222;
    public const FIXTURE_STATS = [];
    public const FIXTURE_USERS = [
        'my-first-user-uuid',
        'my-second-user-uuid',
    ];


    /**
     * @throws Exception
     */
    public function testFullQualified(): void
    {
        $project = new Project(
            [
                'idProject' => self::FIXTURE_ID_PROJECT,
                'idOwner' => self::FIXTURE_ID_OWNER,
                'label' => self::FIXTURE_LABEL,
                'active' => self::FIXTURE_ACTIVE,
                'created' => self::FIXTURE_CREATED,
                'fps' => self::FIXTURE_FPS,
                'resolutionHeight' => self::FIXTURE_RESOLUTION_HEIGHT,
                'resolutionWidth' => self::FIXTURE_RESOLUTION_WIDTH,
                'stats' => self::FIXTURE_STATS,
                'users' => self::FIXTURE_USERS,
            ]
        );

        self::assertTrue($project->hasIdProject());
        self::assertTrue($project->hasIdOwner());
        self::assertTrue($project->hasLabel());
        self::assertTrue($project->hasActive());
        self::assertTrue($project->hasCreated());
        self::assertTrue($project->hasFps());
        self::assertTrue($project->hasResolutionHeight());
        self::assertTrue($project->hasResolutionWidth());
        self::assertTrue($project->hasStats());
        self::assertTrue($project->hasUsers());

        self::assertEquals(self::FIXTURE_ID_PROJECT, $project->getIdProject());
        self::assertEquals(self::FIXTURE_ID_OWNER, $project->getIdOwner());
        self::assertEquals(self::FIXTURE_LABEL, $project->getLabel());
        self::assertEquals(self::FIXTURE_ACTIVE, $project->isActive());
        self::assertEquals(self::FIXTURE_CREATED, $project->getCreated()->format(DateTime::ATOM));
        self::assertEquals(self::FIXTURE_FPS, $project->getFps());
        self::assertEquals(self::FIXTURE_RESOLUTION_HEIGHT, $project->getResolutionHeight());
        self::assertEquals(self::FIXTURE_RESOLUTION_WIDTH, $project->getResolutionWidth());
        self::assertInstanceOf(Stats::class, $project->getStats());
        self::assertEquals(self::FIXTURE_USERS, $project->getUsers());
    }


    /**
     * @throws Exception
     */
    public function testNotExistingDataWillBeNull(): void
    {
        $project = new Project([]);

        self::assertFalse($project->hasIdProject());
        self::assertFalse($project->hasIdOwner());
        self::assertFalse($project->hasLabel());
        self::assertFalse($project->hasActive());
        self::assertFalse($project->hasCreated());
        self::assertFalse($project->hasFps());
        self::assertFalse($project->hasResolutionHeight());
        self::assertFalse($project->hasResolutionWidth());
        self::assertFalse($project->hasStats());
        self::assertFalse($project->hasUsers());

        self::assertNull($project->getIdProject());
        self::assertNull($project->getIdOwner());
        self::assertNull($project->getLabel());
        self::assertNull($project->isActive());
        self::assertNull($project->getCreated());
        self::assertNull($project->getFps());
        self::assertNull($project->getResolutionHeight());
        self::assertNull($project->getResolutionWidth());
        self::assertNull($project->getStats());
        self::assertNull($project->getUsers());
    }


    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testJsonSerializableFullQualified(): void
    {
        $project = new Project(
            [
                'idProject' => self::FIXTURE_ID_PROJECT,
                'idOwner' => self::FIXTURE_ID_OWNER,
                'label' => self::FIXTURE_LABEL,
                'active' => self::FIXTURE_ACTIVE,
                'created' => self::FIXTURE_CREATED,
                'fps' => self::FIXTURE_FPS,
                'resolutionHeight' => self::FIXTURE_RESOLUTION_HEIGHT,
                'resolutionWidth' => self::FIXTURE_RESOLUTION_WIDTH,
                'stats' => self::FIXTURE_STATS,
                'users' => self::FIXTURE_USERS,
            ]
        );


        $array = json_decode(
            json_encode(
                $project,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEquals(self::FIXTURE_ID_PROJECT, $array['idProject']);
        self::assertEquals(self::FIXTURE_ID_OWNER, $array['idOwner']);
        self::assertEquals(self::FIXTURE_LABEL, $array['label']);
        self::assertEquals(self::FIXTURE_ACTIVE, $array['active']);
        self::assertEquals(self::FIXTURE_CREATED, $array['created']);
        self::assertEquals(self::FIXTURE_FPS, $array['fps']);
        self::assertEquals(self::FIXTURE_RESOLUTION_HEIGHT, $array['resolutionHeight']);
        self::assertEquals(self::FIXTURE_RESOLUTION_WIDTH, $array['resolutionWidth']);
        self::assertEquals(self::FIXTURE_STATS, $array['stats']);
        self::assertEquals(self::FIXTURE_USERS, $array['users']);
    }


    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testJsonSerializableNotExistingDataWillBeAbsent(): void
    {
        $project = new Project([]);

        $array = json_decode(
            json_encode(
                $project,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEmpty($array);
    }
}
