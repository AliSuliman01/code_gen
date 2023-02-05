<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\Str;

class CreateControllersPipe
{
    public function handle($json_file, \Closure $next)
    {
        if (file_exists(base_path('stubs/code_gen/controller/controller.stub')))
        $stub = file_get_contents(base_path('stubs/code_gen/controller/controller.stub')) ;
        else
        $stub = file_get_contents(__DIR__ . '/../../stubs/controller/controller.stub');

        foreach ($json_file['tables'] as $table) {

            $controller_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.controller_namespace"];
            $action_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.action_namespace"];
            $model_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.model_namespace"];
            $dto_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.dto_namespace"];
            $request_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.request_namespace"];
            $vm_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.vm_namespace"];


            $finalString = (new Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ controller_namespace }}', $controller_namespace)
                ->replace('{{ action_namespace }}', $action_namespace)
                ->replace('{{ model_namespace }}', $model_namespace)
                ->replace('{{ dto_namespace }}', $dto_namespace)
                ->replace('{{ request_namespace }}', $request_namespace)
                ->replace('{{ vm_namespace }}', $vm_namespace)
                ->replace('{{ instance_name }}', $table['instance_name'])
                ->getString();

            if (str_starts_with($controller_namespace, 'App'))
                $controller_namespace = lcfirst($controller_namespace);

            $file_name = $table['entity_name'] . 'Controller.php';
            $file_path = base_path(str_replace('\\', '/', $controller_namespace) . '/' . $file_name);

            if (!is_dir(dirname($file_path)))
                mkdir(dirname($file_path), 0777, true);
            if (!file_exists($file_path)) {
                $file = fopen($file_path, 'w');
                fwrite($file, $finalString);
                fclose($file);
                echo "\e[0;32mC$file_name Generated.\e[0m\n";
            } else {
                echo "\e[0;35m$file_name existed!\e[0m\n";
            }
        }
        return $next($json_file);
    }
}
