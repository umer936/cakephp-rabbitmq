<?php

use Cake\Core\Configure;
use Cake\Utility\Hash;

Configure::write('Gearman.Jobs.emailWithWorker', [
    'className' => 'DevApp/RabbitMQ.Email',
]);

if (!Configure::read('debug')) {
    return;
}

Configure::write('DebugKit.panels', Hash::merge((array) Configure::read('DebugKit.panels'), [
    'DevApp/RabbitMQ.Job',
]));
