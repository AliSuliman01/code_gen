<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\Str;

class CreateModelsPipe
{
    public function handle($json_file, \Closure $next)
    {
        $base_model_class_name = class_basename(config('code_gen.base_model'));
        $base_model_class = config('code_gen.base_model');
        if (file_exists(base_path('stubs/code_gen/model/model.stub')))
            $stub = file_get_contents(base_path('stubs/code_gen/model/model.stub'));
        else
            $stub = file_get_contents(__DIR__ . '/../../stubs/model/model.stub');

        foreach ($json_file['tables'] as $table) {

            $model_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.model_namespace"];

            $finalString = (new Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ model_namespace }}', $model_namespace)
                ->replace('{{ base_model_class }}', $base_model_class)
                ->replace('{{ base_model_class_name }}', $base_model_class_name)
                ->getString();

            if (str_starts_with($model_namespace, 'App'))
                $model_namespace = lcfirst($model_namespace);

            $file_name = $table['entity_name'] . '.php';
            $file_path = base_path(str_replace('\\', '/', $model_namespace). '/' . $file_name);

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
        return $next($json_file);
    }
}
