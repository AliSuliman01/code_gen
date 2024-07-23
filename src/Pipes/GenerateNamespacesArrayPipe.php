<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\PipeData;

class GenerateNamespacesArrayPipe implements CodeGenPipContract
{
    public static $namespaces = [];

    public function handle(PipeData $pipeData, \Closure $next)
    {
        foreach ($pipeData->json_file['tables'] as $table) {

            self::$namespaces["{$table['table_name']}.model_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config("code_gen.structures.{$pipeData->structureName}.namespaces.models"));
            self::$namespaces["{$table['table_name']}.request_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config("code_gen.structures.{$pipeData->structureName}.namespaces.requests"));
            self::$namespaces["{$table['table_name']}.service_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config("code_gen.structures.{$pipeData->structureName}.namespaces.services"));
            self::$namespaces["{$table['table_name']}.controller_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config("code_gen.structures.{$pipeData->structureName}.namespaces.controllers"));
            self::$namespaces["{$table['table_name']}.route_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config("code_gen.structures.{$pipeData->structureName}.namespaces.routes"));

        }
        return $next($pipeData);
    }
}
