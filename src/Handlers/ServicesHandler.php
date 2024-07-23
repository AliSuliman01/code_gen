<?php

namespace Alisuliman\CodeGenerator\Handlers;

use Alisuliman\CodeGenerator\Pipes\CreateActionsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateControllersPipe;
use Alisuliman\CodeGenerator\Pipes\CreateDTOPipe;
use Alisuliman\CodeGenerator\Pipes\CreateMigrationsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateModelsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateRequestsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateRoutesPipe;
use Alisuliman\CodeGenerator\Pipes\CreateServicePipe;
use Alisuliman\CodeGenerator\Pipes\CreateViewModelsPipe;
use Alisuliman\CodeGenerator\Pipes\GenerateNamespacesArrayPipe;
use Alisuliman\CodeGenerator\Pipes\GeneratePostmanCollectionPipe;
use Alisuliman\CodeGenerator\Pipes\RegisterMigrationsPipe;
use Alisuliman\CodeGenerator\Pipes\RegisterRoutePipe;
use Illuminate\Routing\Pipeline;

class ServicesHandler implements CodeGenHandlerContract
{
    public function handle($json_file)
    {
        app(Pipeline::class)
            ->send($json_file)
            ->through([
                GenerateNamespacesArrayPipe::class,
                CreateMigrationsPipe::class,
                CreateModelsPipe::class,
                CreateServicePipe::class,
                CreateRequestsPipe::class,
                CreateControllersPipe::class,
                CreateRoutesPipe::class,
                RegisterRoutePipe::class,
                RegisterMigrationsPipe::class,
                GeneratePostmanCollectionPipe::class,
            ]);
    }
}