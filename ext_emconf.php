<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'IP Authentication Trigger',
    'description' => 'A frontend plugin triggering the IP Authentication if the user is in a certain IP range',
    'category' => 'plugin',
    'author' => 'Lorenz Ulrich',
    'author_email' => 'lorenz.ulrich@visol.ch',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7',
            'aoe_ipauth' => ''
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
