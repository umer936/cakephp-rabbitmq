<?php

namespace DevApp\RabbitMQ\Test\TestCase\Panel;

use Cake\TestSuite\TestCase;
use DevApp\RabbitMQ\DebugJob;
use DevApp\RabbitMQ\Gearman;
use DevApp\RabbitMQ\Panel\JobPanel;

class JobPanelTest extends TestCase
{
    public function setUp(): void
    {
        DebugJob::$jobs = [
            [
                'name' => 'job',
                'workload' => [],
                'background' => true,
                'priority' => Gearman::PRIORITY_HIGH,
            ],
            [
                'name' => 'job',
                'workload' => [],
                'background' => true,
                'priority' => Gearman::PRIORITY_LOW,
            ],
            [
                'name' => 'job',
                'workload' => [],
                'background' => true,
                'priority' => Gearman::PRIORITY_NORMAL,
            ]
        ];
    }

    public function testSummary()
    {
        $panel = new JobPanel();

        $this->assertEquals(3, $panel->summary());
    }

    public function testData()
    {
        $panel = new JobPanel();

        $this->assertEquals([
            'jobs' => [
                [
                    'name' => 'job',
                    'workload' => [],
                    'background' => true,
                    'priority' => Gearman::PRIORITY_HIGH,
                ],
                [
                    'name' => 'job',
                    'workload' => [],
                    'background' => true,
                    'priority' => Gearman::PRIORITY_LOW,
                ],
                [
                    'name' => 'job',
                    'workload' => [],
                    'background' => true,
                    'priority' => Gearman::PRIORITY_NORMAL,
                ]
            ]
        ], $panel->data());
    }

    public function tearDown(): void
    {
        DebugJob::$jobs = [];
    }
}
