<?php
return [
    'base_model' => \Illuminate\Database\Eloquent\Model::class,
    'base_form_request' => \Illuminate\Foundation\Http\FormRequest::class,

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
    ]
];