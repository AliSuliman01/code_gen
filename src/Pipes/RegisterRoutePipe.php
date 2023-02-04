<?php


namespace Alisuliman\CodeGenerator\Pipes;


class RegisterRoutePipe
{
    public function handle($json_file, \Closure $next)
    {
        $routesFilePath = app_path('Providers/routes.json');

        $routes = [];

        if (file_exists($routesFilePath))
            $routes = json_decode(file_get_contents($routesFilePath), true);

        foreach ($json_file['tables'] as $table) {

            $file_name = $table['table_name'] . '.php';
            $routes_path = config('code_gen.destinations.routes');
            $routePath = str_replace('{{ base_path }}', str_replace('\\', '/', $table['base_path']) ,$routes_path).'/' . $file_name;

            if (!in_array($routePath, $routes)) {
                $routes[] = $routePath;
                echo "\e[0;32m{$table['table_name']}.php route file registered.\e[0m\n";
            }
        }

        file_put_contents($routesFilePath, json_encode($routes,JSON_UNESCAPED_SLASHES));

        return $next($json_file);
    }
}
