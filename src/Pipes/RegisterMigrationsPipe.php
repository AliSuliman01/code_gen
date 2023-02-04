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

            $migrations_path = config('code_gen.destinations.migrations');
            $migrationPath = str_replace('{{ base_path }}', str_replace('\\', '/', $table['base_path']) ,$migrations_path);

            if (!in_array($migrationPath, $migrations)) {
                $migrations[] = $migrationPath;
                echo "\e[0;32m$migrationPath migration source registered.\e[0m\n";
            }
        }

        file_put_contents($migrationsFilePath, json_encode($migrations,JSON_UNESCAPED_SLASHES));

        return $next($json_file);
    }
}
