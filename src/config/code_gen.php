<?php

use Alisuliman\CodeGenerator\Handlers\ModulesHandler;
use Alisuliman\CodeGenerator\Handlers\ServicesHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

return [
    'base_model' => Model::class,
    'base_form_request' => FormRequest::class,

    'namespaces' => [
        'migrations' => 'App\\Modules\\{{ sub_namespace }}\\Migrations',
        'models' => 'App\\Modules\\{{ sub_namespace }}\\Model',
        'dtos' => 'App\\Modules\\{{ sub_namespace }}\\DTO',
        'actions' => 'App\\Modules\\{{ sub_namespace }}\\Actions',
        'viewmodels' => 'App\\Modules\\{{ sub_namespace }}\\ViewModels',
        'requests' => 'App\\Modules\\{{ sub_namespace }}\\Requests',
        'controllers' => 'App\\Modules\\{{ sub_namespace }}\\Controllers',
        'routes' => 'App\\Modules\\{{ sub_namespace }}\\Routes',
    ],

    'postman' => [
        'base_url' => 'localhost'
    ],

    'default' => env('CODE_GEN_DEFAULT_STRUCTURE', 'services'),

    'structures' => [
        'modules' => [
            'handler' => ModulesHandler::class,
            'namespaces' => [
                'migrations' => 'App\\Modules\\{{ sub_namespace }}\\Migrations',
                'models' => 'App\\Modules\\{{ sub_namespace }}\\Model',
                'dtos' => 'App\\Modules\\{{ sub_namespace }}\\DTO',
                'actions' => 'App\\Modules\\{{ sub_namespace }}\\Actions',
                'viewmodels' => 'App\\Modules\\{{ sub_namespace }}\\ViewModels',
                'requests' => 'App\\Modules\\{{ sub_namespace }}\\Requests',
                'controllers' => 'App\\Modules\\{{ sub_namespace }}\\Controllers',
                'routes' => 'App\\Modules\\{{ sub_namespace }}\\Routes',
            ],

        ],
        'services' => [
            'handler' => ServicesHandler::class,
            'namespaces' => [
                'migrations' => 'App\\Modules\\{{ sub_namespace }}\\Migrations',
                'models' => 'App\\Modules\\{{ sub_namespace }}\\Models',
                'services' => 'App\\Modules\\{{ sub_namespace }}\\Services',
                'requests' => 'App\\Modules\\{{ sub_namespace }}\\Requests',
                'controllers' => 'App\\Modules\\{{ sub_namespace }}\\Controllers',
                'routes' => 'App\\Modules\\{{ sub_namespace }}\\Routes',
            ],

        ]
    ]
];