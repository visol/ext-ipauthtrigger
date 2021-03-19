<?php

$EM_CONF['ipauthtrigger'] = [
    'title' => 'IP Authentication Trigger',
    'description' => 'A frontend plugin triggering the IP Authentication if the user is in a certain IP range',
    'category' => 'plugin',
    'author' => 'Lorenz Ulrich',
    'author_email' => 'lorenz.ulrich@visol.ch',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '3.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
            'aoe_ipauth' => '3.0.0-3.99.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
