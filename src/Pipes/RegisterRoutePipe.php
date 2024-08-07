<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\PipeData;

class RegisterRoutePipe implements CodeGenPipContract
{
    public function handle(PipeData $pipeData, \Closure $next)
    {
        $routesFilePath = app_path('Providers/routes.json');

        $routes = [];

        if (file_exists($routesFilePath))
            $routes = json_decode(file_get_contents($routesFilePath), true);

        foreach ($pipeData->json_file['tables'] as $table) {

            $file_name = $table['table_name'] . '.php';
            $routePath = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.route_namespace"] . '/' . $file_name;

            $routePath = str_replace('\\', '/', $routePath);
            
            if (str_starts_with($routePath, 'App'))
                $routePath = lcfirst($routePath);

            if (!in_array($routePath, $routes)) {
                $routes[] = $routePath;
                echo "\e[0;32m{$table['table_name']}.php route file registered.\e[0m\n";
            }
        }

        file_put_contents($routesFilePath, json_encode($routes,JSON_UNESCAPED_SLASHES));

        return $next($pipeData);
    }
}
