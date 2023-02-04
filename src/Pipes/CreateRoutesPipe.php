<?php


namespace Alisuliman\CodeGenerator\Pipes;


class CreateRoutesPipe
{
    public function handle($json_file, \Closure $next)
    {
        foreach ($json_file['tables'] as $table) {
            $stub = file_get_contents(__DIR__ . '/../../stubs/routes.stub');

            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $workingString = str_replace('{{ base_path }}', $table['base_path'], $workingString);
            $finalString = str_replace('{{ table_name }}', $table['table_name'], $workingString);

            $file_name = $table['table_name'] . '.php';
            $routes_path = config('code_gen.destinations.routes');
            $file_path = base_path(str_replace('{{ base_path }}', str_replace('\\', '/', $table['base_path']) ,$routes_path).'/' . $file_name);
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
