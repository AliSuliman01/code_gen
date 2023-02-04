<?php
return [
    'base_model' => \Illuminate\Database\Eloquent\Model::class,
    'base_form_request' => \Illuminate\Foundation\Http\FormRequest::class,

    'destinations' => [
        'migrations' => 'app/modules/{{ base_path }}/Migrations',
        'models' => 'app/modules/{{ base_path }}/Model',
        'dtos' => 'app/modules/{{ base_path }}/DTO',
        'actions' => 'app/modules/{{ base_path }}/Actions',
        'viewmodels' => 'app/modules/{{ base_path }}/ViewModels',
        'requests' => 'app/modules/{{ base_path }}/Requests',
        'controllers' => 'app/modules/{{ base_path }}/Controllers',
        'routes' => 'app/modules/{{ base_path }}/Routes',
    ],

];