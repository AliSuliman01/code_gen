<?php

namespace Alisuliman\CodeGen\Commands;

use Illuminate\Console\Command;

class PublishCodeGenerateTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code_gen:template';

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
        copy(__DIR__.'/../../template/code_gen.json',base_path('code_gen.json'));
        echo "\e[0;32mDone.\e[0m\n";
    }
}
