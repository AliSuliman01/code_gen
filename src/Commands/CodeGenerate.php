<?php

namespace Alisuliman\CodeGen\Commands;


use Alisuliman\CodeGen\Pipes\CreateActionsPipe;
use Alisuliman\CodeGen\Pipes\CreateControllersPipe;
use Alisuliman\CodeGen\Pipes\CreateDTOPipe;
use Alisuliman\CodeGen\Pipes\CreateMigrationsPipe;
use Alisuliman\CodeGen\Pipes\CreateModelsPipe;
use Alisuliman\CodeGen\Pipes\CreateRequestsPipe;
use Alisuliman\CodeGen\Pipes\CreateRoutesPipe;
use Alisuliman\CodeGen\Pipes\CreateViewModelsPipe;
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
                CreateMigrationsPipe::class,
                CreateModelsPipe::class,
                CreateDTOPipe::class,
                CreateActionsPipe::class,
                CreateViewModelsPipe::class,
                CreateRequestsPipe::class,
                CreateControllersPipe::class,
                CreateRoutesPipe::class,
            ])
            ->thenReturn();

        echo "\e[0;32m------Code generating finished------\e[0m";
    }
}
