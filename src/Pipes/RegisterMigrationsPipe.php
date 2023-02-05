<?php


namespace Alisuliman\CodeGenerator\Pipes;


class RegisterMigrationsPipe
{
    public function handle($json_file, \Closure $next)
    {
        $migrationsFilePath = app_path('Providers/migrations.json');

        $migrations = [];

        if (file_exists($migrationsFilePath))
            $migrations = json_decode(file_get_contents($migrationsFilePath), true);


        foreach ($json_file['tables'] as $table) {

            $migrations_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.migration_namespace"];

            $migrations_namespace = str_replace('\\', '/', $migrations_namespace);
            
            if (str_starts_with($migrations_namespace, 'App'))
                $migrations_namespace = lcfirst($migrations_namespace);

            if (!in_array($migrations_namespace, $migrations)) {
                $migrations[] = $migrations_namespace;
                echo "\e[0;32m$migrations_namespace migration source registered.\e[0m\n";
            }
        }

        file_put_contents($migrationsFilePath, json_encode($migrations, JSON_UNESCAPED_SLASHES));

        return $next($json_file);
    }
}
