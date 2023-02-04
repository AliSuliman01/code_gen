<?php


namespace Alisuliman\CodeGenerator\Pipes;


class CreateDTOPipe
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

    public function handle($json_file, \Closure $next)
    {
        foreach ($json_file['tables'] as $table) {
            $stub = file_get_contents(__DIR__ . '/../../stubs/dto/dto.stub');

            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $workingString = str_replace('{{ base_path }}', $table['base_path'], $workingString);
            $workingString = str_replace('{{ dto_props }}', $this->prepareDtoProps($table['columns']), $workingString);
            $finalString = str_replace('{{ dto_from_request }}', $this->prepareDtoFromRequest($table['columns']), $workingString);

            $file_name = $table['entity_name'] . 'DTO.php';
            $dto_path = config('code_gen.destinations.dtos');
            $file_path = base_path(str_replace('{{ base_path }}', str_replace('\\', '/', $table['base_path']) ,$dto_path).'/' . $file_name);
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
