<?php


namespace Alisuliman\CodeGenerator\Pipes;


class CreateMigrationsPipe
{
    public function handle($json_file, \Closure $next)
    {
        foreach ($json_file['tables'] as $table) {
            $stub = file_get_contents(__DIR__ . '/../../stubs/migration/migration.create.stub');
            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $finalString = str_replace('{{ table_name }}', $table['table_name'], $workingString);
            $static_part_of_file_name = '_create_' . $table['table_name'] . '_table.php';
            $file_name = date('Y_m_d_') . time() % 1000000 . $static_part_of_file_name;

            $migrations_path = config('code_gen.destinations.migrations');

            $file_path = base_path(str_replace('{{ base_path }}', str_replace('\\', '/', $table['base_path']) ,$migrations_path).'/' . $file_name);

            if (!is_dir(dirname($file_path)))
                mkdir(dirname($file_path), 0777, true);

            if (count(glob(base_path($migrations_path) . "/*$static_part_of_file_name", GLOB_BRACE)) == 0) {

                $file = fopen($file_path, 'w');
                fwrite($file, $finalString);
                fclose($file);

                echo "\e[0;32m$file_name Generated.\e[0m\n";
            } else {
                echo "\e[0;35m$static_part_of_file_name existed!\e[0m\n";
            }
        }

        return $next($json_file);
    }
}
