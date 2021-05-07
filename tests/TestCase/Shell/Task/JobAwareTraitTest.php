<?php

namespace DevApp\RabbitMQ\Test\TestCase\Shell\Task;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use DevApp\RabbitMQ\Gearman;
use DevApp\RabbitMQ\JobAwareTrait;

class JobAwareTraitClass
{
    use JobAwareTrait;
}

class JobAwareTraitTest extends TestCase
{
    public function setUp(): void
    {
        Configure::write('Gearman.Servers', [
            '127.0.0.1',
        ]);
    }

    public function testGearmanClient()
    {
        $jobAwareTraitClass = new JobAwareTraitClass();
        $gearmanClient = $jobAwareTraitClass->gearmanClient();

        $this->assertInstanceOf('\GearmanClient', $gearmanClient);
    }

    public function testGearmanWorker()
    {
        $jobAwareTraitClass = new JobAwareTraitClass();
        $gearmanClient = $jobAwareTraitClass->gearmanWorker();

        $this->assertInstanceOf('\GearmanWorker', $gearmanClient);
    }

    public function testInvalidServerConfiguration()
    {
        $this->expectException(\Cake\Core\Exception\Exception::class);
        $this->expectExceptionMessage("Invalid Gearman configuration: you must configure at least one server");
        Configure::drop('Gearman.Servers');
        Configure::write('Gearman.Servers', []);

        $jobAwareTraitClass = new JobAwareTraitClass();
        $gearmanClient = $jobAwareTraitClass->gearmanWorker();

        $this->assertInstanceOf('\GearmanWorker', $gearmanClient);
    }

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($job, $workload, $background, $priority)
    {
        $jobAwareTraitClass = new JobAwareTraitClass();
        $result = $jobAwareTraitClass->execute($job, $workload, $background, $priority);

        if ($background) {
            $this->assertContains(':', $result);
        } else {
            $this->assertEquals($workload, $result);
        }
    }

    public function executeProvider()
    {
        return [
            ['testJob', ['data'], false, Gearman::PRIORITY_NORMAL],
            ['testJob', ['data'], true, Gearman::PRIORITY_NORMAL],
            ['testJob', ['data'], false, Gearman::PRIORITY_LOW],
            ['testJob', ['data'], true, Gearman::PRIORITY_HIGH]
        ];
    }

    public function tearDown(): void
    {
        Configure::drop('Gearman');
    }
}
