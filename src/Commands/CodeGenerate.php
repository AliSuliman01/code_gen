<?php

namespace Alisuliman\CodeGenerator\Commands;


use Alisuliman\CodeGenerator\Pipes\CreateActionsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateControllersPipe;
use Alisuliman\CodeGenerator\Pipes\CreateDTOPipe;
use Alisuliman\CodeGenerator\Pipes\CreateMigrationsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateModelsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateRequestsPipe;
use Alisuliman\CodeGenerator\Pipes\CreateRoutesPipe;
use Alisuliman\CodeGenerator\Pipes\CreateViewModelsPipe;
use Alisuliman\CodeGenerator\Pipes\GenerateNamespacesArrayPipe;
use Alisuliman\CodeGenerator\Pipes\GeneratePostmanCollectionPipe;
use Alisuliman\CodeGenerator\Pipes\RegisterMigrationsPipe;
use Alisuliman\CodeGenerator\Pipes\RegisterRoutePipe;
use Illuminate\Console\Command;
use Illuminate\Routing\Pipeline;

class CodeGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code_gen {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate code from json file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "\e[0;32m------Code generating started------\e[0m\n";

        $json_file = json_decode(file_get_contents(base_path($this->argument('file') ?? 'code_gen.json')), true);

        app(Pipeline::class)
            ->send($json_file)
            ->through([
                GenerateNamespacesArrayPipe::class,
                CreateMigrationsPipe::class,
                CreateModelsPipe::class,
                CreateDTOPipe::class,
                CreateActionsPipe::class,
                CreateViewModelsPipe::class,
                CreateRequestsPipe::class,
                CreateControllersPipe::class,
                CreateRoutesPipe::class,
                RegisterRoutePipe::class,
                RegisterMigrationsPipe::class,
                GeneratePostmanCollectionPipe::class,
            ])
            ->thenReturn();

        echo "\e[0;32m------Code generating finished------\e[0m";
    }
}
