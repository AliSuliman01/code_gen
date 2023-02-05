<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Illuminate\Support\Str;

class GenerateNamespacesArrayPipe
{
    public static $namespaces = [];

    public function handle($json_file, \Closure $next)
    {
        foreach ($json_file['tables'] as $table) {

            self::$namespaces["{$table['table_name']}.action_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.actions'));
            self::$namespaces["{$table['table_name']}.migration_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.migrations'));
            self::$namespaces["{$table['table_name']}.model_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.models'));
            self::$namespaces["{$table['table_name']}.dto_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.dtos'));
            self::$namespaces["{$table['table_name']}.vm_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.viewmodels'));
            self::$namespaces["{$table['table_name']}.request_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.requests'));
            self::$namespaces["{$table['table_name']}.controller_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.controllers'));
            self::$namespaces["{$table['table_name']}.route_namespace"] = str_replace('{{ sub_namespace }}', $table['sub_namespace'], config('code_gen.namespaces.routes'));

        }
        return $next($json_file);
    }
}
