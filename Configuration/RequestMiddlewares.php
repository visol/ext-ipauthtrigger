<?php

return [
    'frontend' => [
        'visol/ipauthtrigger/frontend/async-trigger' => [
            'target' => \Visol\Ipauthtrigger\Middleware\AsyncTriggerMiddleware::class,
            'after' => [
                'typo3/cms-frontend/authentication',
            ],
            'before' => [
                'typo3/cms-adminpanel/initiator',
            ],
        ]
    ]
];
