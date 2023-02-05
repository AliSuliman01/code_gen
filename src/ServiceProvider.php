<?php


namespace Alisuliman\CodeGenerator;


use Alisuliman\CodeGenerator\Commands\CodeGenerate;
use Alisuliman\CodeGenerator\Commands\PublishCodeGenerateTemplate;
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
            __DIR__ . '/config/code_gen.php' => config_path('code_gen.php'),
        ], 'code_gen_configs');

        $this->publishes([
            __DIR__ . '/../stubs' => base_path('stubs/code_gen'),
        ], 'code_gen_stubs');

        $this->publishes([
            __DIR__ . '/../template/code_gen.json' => base_path('code_gen.json'),
        ], 'code_gen_template');
    }

}
