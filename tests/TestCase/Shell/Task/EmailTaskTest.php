<?php

namespace DevApp\RabbitMQ\Test\TestCase\Shell\Task;

use Cake\Mailer\Email;
use Cake\TestSuite\TestCase;
use DevApp\RabbitMQ\Shell\Task\EmailTask;

class EmailTaskTest extends TestCase
{
    public function setUp(): void
    {
        Email::configTransport('default', [
            'className' => 'Debug',
        ]);
    }

    public function testMissingTransport()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Transport config \"default\" is missing.");
        Email::dropTransport('default');

        $emailTask = new EmailTask();
        $emailTask->main([
            'email' => new Email(),
            'fullBaseUrl' => 'http://example.com',
            'transport' => 'default',
        ]);
    }

    public function testNoFullBaseUrl()
    {
        $emailTask = new EmailTask();

        $emailTask->main([
            'email' => new Email([
                'from' => 'from@example.com',
                'to' => 'to@example.com',
            ]),
            'transport' => 'default',
        ]);
    }

    public function testUsingDebugTransport()
    {
        $emailTask = new EmailTask();

        $result = $emailTask->main([
            'email' => new Email([
                'from' => 'from@example.com',
                'to' => 'to@example.com',
            ]),
            'fullBaseUrl' => 'http://example.com',
            'transport' => 'default',
        ]);

        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('headers', $result);
    }

    public function tearDown(): void
    {
        Email::dropTransport('default');
    }
}
