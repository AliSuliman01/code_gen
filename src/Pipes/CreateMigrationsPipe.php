<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\Str;

class CreateMigrationsPipe
{
    public function handle($json_file, \Closure $next)
    {
        if (file_exists(base_path('stubs/code_gen/migration/migration.create.stub')))
            $stub = file_get_contents(base_path('stubs/code_gen/migration/migration.create.stub'));
        else
            $stub = file_get_contents(__DIR__ . '/../../stubs/migration/migration.create.stub');

        foreach ($json_file['tables'] as $table) {
            $migration_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.migration_namespace"];

            $finalString = (new Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ table_name }}', $table['table_name'])
                ->getString();

            $static_part_of_file_name = '_create_' . $table['table_name'] . '_table.php';

            $file_name = date('Y_m_d_') . time() % 1000000 . $static_part_of_file_name;

            if (str_starts_with($migration_namespace, 'App'))
                $migration_namespace = lcfirst($migration_namespace);

            $file_path = base_path(str_replace('\\', '/', $migration_namespace) . '/' . $file_name);

            if (!is_dir(dirname($file_path)))
                mkdir(dirname($file_path), 0777, true);

            if (count(glob(base_path(str_replace('\\', '/', $migration_namespace)) . "/*$static_part_of_file_name", GLOB_BRACE)) == 0) {

                $file = fopen($file_path, 'w');
                fwrite($file, $finalString);
                fclose($file);

                echo "\e[0;32m$file_name Generated.\e[0m\n";
            } else {
                echo "\e[0;35m$static_part_of_file_name existed!\e[0m\n";
            }
            sleep(1);
        }

        return $next($json_file);
    }
}
