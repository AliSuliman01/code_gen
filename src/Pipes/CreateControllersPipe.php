<?php


namespace Alisuliman\CodeGenerator\Pipes;


class CreateControllersPipe
{
    public function handle($json_file, \Closure $next)
    {
        foreach ($json_file['tables'] as $table) {
            $stub = file_get_contents(__DIR__ . '/../../stubs/controller/controller.stub');

            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $workingString = str_replace('{{ base_path }}', $table['base_path'], $workingString);
            $finalString = str_replace('{{ instance_name }}', $table['instance_name'], $workingString);

            $file_name = $table['entity_name'] . 'Controller.php';
            $file_path = app_path('Http/Controllers/' . str_replace('\\', '/', $table['base_path']) . '/' . $file_name);
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
