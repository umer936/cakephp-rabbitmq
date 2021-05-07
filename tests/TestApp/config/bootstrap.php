<?php

use Cake\Core\Configure;

Configure::write('App.namespace', 'TestApp');
Configure::write('Gearman.Jobs', [
    'testJob' => [
        'className' => 'TestJob',
    ],
]);
