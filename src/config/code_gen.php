<?php
return [
    'base_model' => \Illuminate\Database\Eloquent\Model::class,
    'base_form_request' => \Illuminate\Foundation\Http\FormRequest::class,

    'destinations' => [
        'migrations' => 'app/Modules/{{ base_path }}/Migrations',
        'models' => 'app/Modules/{{ base_path }}/Model',
        'dtos' => 'app/Modules/{{ base_path }}/DTO',
        'actions' => 'app/Modules/{{ base_path }}/Actions',
        'viewmodels' => 'app/Modules/{{ base_path }}/ViewModels',
        'requests' => 'app/Modules/{{ base_path }}/Requests',
        'controllers' => 'app/Modules/{{ base_path }}/Controllers',
        'routes' => 'app/Modules/{{ base_path }}/Routes',
    ],

];