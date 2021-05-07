<?php

namespace DevApp\RabbitMQ\Panel;

use DevApp\RabbitMQ\DebugJob;
use DebugKit\DebugPanel;

class JobPanel extends DebugPanel
{

    public $plugin = 'DevApp/RabbitMQ';

    /**
     * {@inheritDoc}
     */
    public function summary()
    {
        return count(DebugJob::$jobs);
    }

    /**
     * {@inheritDoc}
     */
    public function data()
    {
        return [
            'jobs' => DebugJob::$jobs,
        ];
    }
}
