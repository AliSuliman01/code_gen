<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\PipeData;

interface CodeGenPipContract
{
    public function handle(PipeData $pipeData, \Closure $next);
}