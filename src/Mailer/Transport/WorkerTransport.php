<?php

namespace DevApp\RabbitMQ\Mailer\Transport;

use Cake\Core\Configure;
use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Message;
use DevApp\RabbitMQ\JobAwareTrait;

class WorkerTransport extends AbstractTransport
{

    use JobAwareTrait;

    /**
     * Default config for this class.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'transport' => 'default',
        'background' => true,
    ];

    /**
     * Send email.
     *
     * @param \Cake\Mailer\Message $message Email instance.
     *
     * @return array|bool
     */
    public function send(Message $message): array
    {
        $result = $this->execute('emailWithWorker', [
            'email' => $message,
            'transport' => $this->getConfig('transport'),
            'fullBaseUrl' => Configure::read('App.fullBaseUrl'),
        ], $this->getConfig('background'));

        if (!$this->getConfig('background')) {
            return $result;
        }

        return true;
    }
}
