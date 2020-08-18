<?php

declare(strict_types=1);

namespace Tracking3\Core\ClientTest\Project;

use DateTime;
use Exception;
use JsonException;
use PHPUnit\Framework\TestCase;
use Tracking3\Core\Client\Project\Stats;

class StatsTest extends TestCase
{
    public const FIXTURE_FILES = 40;
    public const FIXTURE_FILES_TRENDS = 3;
    public const FIXTURE_RUNTIME = [
        'y' => 1,
        'd' => 304,
    ];
    public const FIXTURE_RUNTIME_END = '2025-08-22T12:04:33+00:00';
    public const FIXTURE_RUNTIME_START = '2019-10-12T22:44:03+00:00';
    public const FIXTURE_SIZE = 2898892;
    public const FIXTURE_SIZE_LIMIT = 5368709120;
    public const FIXTURE_SIZE_TREND = 34588;
    public const FIXTURE_USERS = 25;
    public const FIXTURE_USERS_TREND = 2;


    /**
     * @throws Exception
     */
    public function testFullQualified(): void
    {
        $stats = new Stats(
            [
                'files' => self::FIXTURE_FILES,
                'filesTrend' => self::FIXTURE_FILES_TRENDS,
                'runtime' => self::FIXTURE_RUNTIME,
                'runtimeEnd' => self::FIXTURE_RUNTIME_END,
                'runtimeStart' => self::FIXTURE_RUNTIME_START,
                'size' => self::FIXTURE_SIZE,
                'sizeLimit' => self::FIXTURE_SIZE_LIMIT,
                'sizeTrend' => self::FIXTURE_SIZE_TREND,
                'users' => self::FIXTURE_USERS,
                'usersTrend' => self::FIXTURE_USERS_TREND,
            ]
        );

        self::assertTrue($stats->hasFiles());
        self::assertTrue($stats->hasFilesTrend());
        self::assertTrue($stats->hasRuntime());
        self::assertTrue($stats->hasRuntimeEnd());
        self::assertTrue($stats->hasRuntimeStart());
        self::assertTrue($stats->hasSize());
        self::assertTrue($stats->hasSizeLimit());
        self::assertTrue($stats->hasSizeTrend());
        self::assertTrue($stats->hasUsers());
        self::assertTrue($stats->hasUsersTrend());

        self::assertEquals(self::FIXTURE_FILES, $stats->getFiles());
        self::assertEquals(self::FIXTURE_FILES_TRENDS, $stats->getFilesTrend());
        self::assertEquals(self::FIXTURE_RUNTIME, $stats->getRuntime());
        self::assertEquals(self::FIXTURE_RUNTIME_END, $stats->getRuntimeEnd()->format(DateTime::ATOM));
        self::assertEquals(self::FIXTURE_RUNTIME_START, $stats->getRuntimeStart()->format(DateTime::ATOM));
        self::assertEquals(self::FIXTURE_SIZE, $stats->getSize());
        self::assertEquals(self::FIXTURE_SIZE_LIMIT, $stats->getSizeLimit());
        self::assertEquals(self::FIXTURE_SIZE_TREND, $stats->getSizeTrend());
        self::assertEquals(self::FIXTURE_USERS, $stats->getUsers());
        self::assertEquals(self::FIXTURE_USERS_TREND, $stats->getUsersTrend());
    }


    /**
     * @throws Exception
     */
    public function testNotExistingDataWillBeNull(): void
    {
        $stats = new Stats([]);

        self::assertFalse($stats->hasFiles());
        self::assertFalse($stats->hasFilesTrend());
        self::assertFalse($stats->hasRuntime());
        self::assertFalse($stats->hasRuntimeEnd());
        self::assertFalse($stats->hasRuntimeStart());
        self::assertFalse($stats->hasSize());
        self::assertFalse($stats->hasSizeLimit());
        self::assertFalse($stats->hasSizeTrend());
        self::assertFalse($stats->hasUsers());
        self::assertFalse($stats->hasUsersTrend());

        self::assertNull($stats->getFiles());
        self::assertNull($stats->getFilesTrend());
        self::assertNull($stats->getRuntime());
        self::assertNull($stats->getRuntimeEnd());
        self::assertNull($stats->getRuntimeStart());
        self::assertNull($stats->getSize());
        self::assertNull($stats->getSizeLimit());
        self::assertNull($stats->getSizeTrend());
        self::assertNull($stats->getUsers());
        self::assertNull($stats->getUsersTrend());
    }


    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testJsonSerializableFullQualified(): void
    {
        $stats = new Stats(
            [
                'files' => self::FIXTURE_FILES,
                'filesTrend' => self::FIXTURE_FILES_TRENDS,
                'runtime' => self::FIXTURE_RUNTIME,
                'runtimeEnd' => self::FIXTURE_RUNTIME_END,
                'runtimeStart' => self::FIXTURE_RUNTIME_START,
                'size' => self::FIXTURE_SIZE,
                'sizeLimit' => self::FIXTURE_SIZE_LIMIT,
                'sizeTrend' => self::FIXTURE_SIZE_TREND,
                'users' => self::FIXTURE_USERS,
                'usersTrend' => self::FIXTURE_USERS_TREND,
            ]
        );


        $array = json_decode(
            json_encode(
                $stats,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEquals(self::FIXTURE_FILES, $array['files']);
        self::assertEquals(self::FIXTURE_FILES_TRENDS, $array['filesTrend']);
        self::assertEquals(self::FIXTURE_RUNTIME, $array['runtime']);
        self::assertEquals(self::FIXTURE_RUNTIME_END, $array['runtimeEnd']);
        self::assertEquals(self::FIXTURE_RUNTIME_START, $array['runtimeStart']);
        self::assertEquals(self::FIXTURE_SIZE, $array['size']);
        self::assertEquals(self::FIXTURE_SIZE_LIMIT, $array['sizeLimit']);
        self::assertEquals(self::FIXTURE_SIZE_TREND, $array['sizeTrend']);
        self::assertEquals(self::FIXTURE_USERS, $array['users']);
        self::assertEquals(self::FIXTURE_USERS_TREND, $array['usersTrend']);
    }


    /**
     * @throws JsonException
     * @throws Exception
     */
    public function testJsonSerializableNotExistingDataWillBeAbsent(): void
    {
        $stats = new Stats([]);

        $array = json_decode(
            json_encode(
                $stats,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        self::assertEmpty($array);
    }
}
