<?php


namespace Alisuliman\CodeGen;


use Alisuliman\CodeGen\Commands\CodeGenerate;
use Illuminate\Contracts\Http\Kernel;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot(Kernel $kernel)
    {
        $this->commands([
            CodeGenerate::class
        ]);

        $this->mergeConfigFrom(__DIR__ . '/config/code_gen.php', 'code_gen');
    }

}