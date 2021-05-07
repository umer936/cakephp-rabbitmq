<?php

namespace DevApp\RabbitMQ\Test\TestCase\Mailer\Transport;

use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\TestSuite\TestCase;

class WorkerTransportTest extends TestCase
{
    public function setUp(): void
    {
        Configure::write('Gearman.Servers', [
            '127.0.0.1',
        ]);
    }

    public function testSendBackground()
    {
        $mock = $this->getMockBuilder('DevApp\RabbitMQ\Mailer\Transport\WorkerTransport')
            ->addMethods(['execute']);

        $email = new Email();

        $transport = $mock->getMock();
        $transport->expects($this->once())
            ->method('execute')
            ->with('emailWithWorker', [
                'email' => $email,
                'transport' => 'default',
                'fullBaseUrl' => 'http://localhost',
            ]);

        $this->assertTrue($transport->send($email));
    }

    public function testSendForeground()
    {
        $mock = $this->getMockBuilder('DevApp\RabbitMQ\Mailer\Transport\WorkerTransport')
            ->addMethods(['execute'])
            ->setConstructorArgs([
                [
                    'background' => false,
                ],
            ]);

        $email = new Email();

        $transport = $mock->getMock();
        $transport->expects($this->once())
            ->method('execute')
            ->with('emailWithWorker', [
                'email' => $email,
                'transport' => 'default',
                'fullBaseUrl' => 'http://localhost'
            ])
            ->willReturn(true);

        $this->assertTrue($transport->send($email));
    }

    public function tearDown(): void
    {
        Configure::drop('Gearman');
    }
}
