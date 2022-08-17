<?php


namespace Alisuliman\CodeGen\Pipes;


class RegisterRoutePipe
{
    public function handle($json_file, \Closure $next)
    {
        $routesFilePath = app_path('Providers/routes.json');

        $routes = [];

        if (file_exists($routesFilePath))
            $routes = json_decode(file_get_contents($routesFilePath),true);

        foreach ($json_file['tables'] as $table) {

            $routes[] = "routes/" . str_replace("\\", "/", $table['base_path']) . "/" . $table['table_name'] . ".php";

        }

        file_put_contents($routesFilePath,$routes);

        return $next($json_file);
    }
}
