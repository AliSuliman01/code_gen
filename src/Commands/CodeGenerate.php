<?php

namespace Alisuliman\CodeGenerator\Commands;


use Alisuliman\CodeGenerator\Helpers\PipeData;
use Illuminate\Console\Command;

class CodeGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code_gen {s?}';

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

        $json_file = json_decode(file_get_contents(base_path('code_gen.json')), true);
        $structureName = $this->argument('s') ?? config('code_gen.default');
        $structure = config('code_gen.structures')[$structureName];

        $pipeData = new PipeData();
        $pipeData->json_file = $json_file;
        $pipeData->namespaces = $structure['namespaces'];
        $pipeData->structureName = $structureName;

        $handler = $structure['handler']();
        $handler->handle($pipeData);

        echo "\e[0;32m------Code generating finished------\e[0m";

        return 0;
    }
}
