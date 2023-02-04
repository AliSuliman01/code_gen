<?php


namespace Alisuliman\CodeGenerator\Pipes;


class CreateModelsPipe
{
    public function handle($json_file, \Closure $next)
    {
        $base_model_class_name = class_basename(config('code_gen.base_model'));
        foreach ($json_file['tables'] as $table) {
            $stub = file_get_contents(__DIR__ . '/../../stubs/model/model.stub');

            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $workingString = str_replace('{{ base_path }}', $table['base_path'], $workingString);
            $workingString = str_replace('{{ base_model_class }}', config('code_gen.base_model'), $workingString);
            $finalString = str_replace('{{ base_model_class_name }}', $base_model_class_name, $workingString);

            $file_name = $table['entity_name'] . '.php';

            $models_path = config('code_gen.destinations.models');

            $file_path = base_path(str_replace('{{ base_path }}', str_replace('\\', '/', $table['base_path']) ,$models_path).'/' . $file_name);
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
