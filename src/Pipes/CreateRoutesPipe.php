<?php

namespace Alisuliman\CodeGenerator\Pipes;

use Alisuliman\CodeGenerator\Helpers\PipeData;
class CreateRoutesPipe implements CodeGenPipContract
{
    public function handle(PipeData $pipeData, \Closure $next)
    {
        if (file_exists(base_path('stubs/code_gen/routes.stub')))
            $stub = file_get_contents(base_path('stubs/code_gen/routes.stub'));
        else
            $stub = file_get_contents(__DIR__ . '/../../stubs/routes.stub');

        foreach ($pipeData->json_file['tables'] as $table) {

            $controller_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.controller_namespace"];
            $route_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.route_namespace"];

            $finalString = (new \Alisuliman\CodeGenerator\Helpers\Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ controller_namespace }}', $controller_namespace)
                ->replace('{{ table_name }}', $table['table_name'])
                ->getString();

            if (str_starts_with($route_namespace, 'App'))
                $route_namespace = lcfirst($route_namespace);

            $file_name = $table['table_name'] . '.php';
            $file_path = base_path(str_replace('\\', '/', $route_namespace) . '/' . $file_name);

            if (!is_dir(dirname($file_path)))
                mkdir(dirname($file_path), 0777, true);
            if (!file_exists($file_path)) {
                $file = fopen($file_path, 'w');
                fwrite($file, $finalString);
                fclose($file);
                echo "\e[0;32m$file_name Generated.\e[0m\n";
            } else {
                echo "\e[0;35m$file_name existed!\e[0m\n";
            }
        }
        return $next($pipeData);
    }
}
