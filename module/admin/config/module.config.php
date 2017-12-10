<?php

namespace Admin;

use Zend\Router\Http\Segment;

return 

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'admin' => __DIR__ . '/../view',
        ],
    ],
];