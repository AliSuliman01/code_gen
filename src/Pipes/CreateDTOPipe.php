<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\PipeData;
use Alisuliman\CodeGenerator\Helpers\Str;

class CreateDTOPipe implements CodeGenPipContract
{
    private function prepareDtoProps($columns)
    {
        $replaceString = '';

        foreach ($columns as $column) {
            $replaceString .= "\t/* @var {$column['type']}|null */\n\tpublic \${$column['name']};\n";
        }
        return $replaceString;
    }

    private function prepareDtoFromRequest($columns)
    {
        $replaceString = '';

        foreach ($columns as $column) {
            $replaceString .= "\t\t\t'{$column['name']}'\t\t\t\t=> \$request['{$column['name']}'] ?? null ,\n";
        }
        return $replaceString;
    }

    public function handle(PipeData $pipeData, \Closure $next)
    {
        if (file_exists(base_path('stubs/code_gen/dto/dto.stub')))
            $stub = file_get_contents(base_path('stubs/code_gen/dto/dto.stub'));
        else
            $stub = file_get_contents(__DIR__ . '/../../stubs/dto/dto.stub');
        
        foreach ($pipeData->json_file['tables'] as $table) {

            $dto_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.dto_namespace"];

            $finalString = (new Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ dto_namespace }}', $dto_namespace)
                ->replace('{{ dto_props }}', $this->prepareDtoProps($table['columns']))
                ->replace('{{ dto_from_request }}', $this->prepareDtoFromRequest($table['columns']))
                ->getString();

            if (str_starts_with($dto_namespace, 'App'))
                $dto_namespace = lcfirst($dto_namespace);

            $file_name = $table['entity_name'] . 'DTO.php';
            $file_path = base_path(str_replace('\\', '/', $dto_namespace) . '/' . $file_name);

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


        return $next($pipeData);
    }
}
