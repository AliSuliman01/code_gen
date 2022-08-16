<?php


namespace Alisuliman\CodeGen;


use Alisuliman\CodeGen\Commands\CodeGenerate;
use Alisuliman\CodeGen\Commands\PublishCodeGenerateTemplate;
use Illuminate\Contracts\Http\Kernel;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot(Kernel $kernel)
    {
        $this->commands([
            CodeGenerate::class,
            PublishCodeGenerateTemplate::class
        ]);

        $this->mergeConfigFrom(__DIR__ . '/config/code_gen.php', 'code_gen');

        $this->publishes([
            __DIR__ . '/config/code_gen.php' => config_path('code_gen.php')
        ], 'code_gen_configs');
    }

}