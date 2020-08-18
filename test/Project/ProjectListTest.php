<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Project;

use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Project\ProjectList;

class ProjectListTest extends TestCase
{

    public const FIXTURE_ID_PROJECT_1 = 'my-first-project-uuid';
    public const FIXTURE_ID_PROJECT_2 = 'my-second-project-uuid';


    public function testCreateEmptyList(): void
    {
        $list = new ProjectList();

        self::assertTrue($list->isEmpty());
    }


    public function testCreateList(): void
    {
        $projects = [
            [
                'idProject' => self::FIXTURE_ID_PROJECT_1,
            ],
            [
                'idProject' => self::FIXTURE_ID_PROJECT_2,
            ],
        ];

        $list = new ProjectList($projects);

        self::assertCount(2, $list);

        self::assertEquals(self::FIXTURE_ID_PROJECT_1, $list->first()->getIdProject());
        self::assertEquals(self::FIXTURE_ID_PROJECT_2, $list->next()->getIdProject());
    }
}
