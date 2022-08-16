<?php


namespace Alisuliman\CodeGen\Pipes;


use Illuminate\Support\Str;

class CreateRequestsPipe
{
    private $base_form_request_class;
    private $base_form_request_class_name;

    public function __construct()
    {
        $this->base_form_request_class = config('code_gen.base_form_request');
        $this->base_form_request_class_name = basename($this->base_form_request_class);
    }

    private function prepareRequestRules($columns)
    {
        $replaceString = '';

        foreach ($columns as $column) {
            $replaceString .= "\t\t\t'{$column['name']}'\t\t\t\t=> '{$column['validation_rules']}' ,\n";
        }
        return $replaceString;
    }

    private function handleSingleStub($stub, $json_file, $prefix)
    {

        foreach ($json_file['tables'] as $table) {

            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $workingString = str_replace('{{ base_path }}', $table['base_path'], $workingString);
            $workingString = str_replace('{{ base_form_request_class }}', $this->base_form_request_class, $workingString);
            $workingString = str_replace('{{ base_form_request_class_name }}', $this->base_form_request_class_name, $workingString);
            $finalString = str_replace('{{ request_rules }}', $this->prepareRequestRules($table['columns']), $workingString);

            $file_name = $prefix . $table['entity_name'] . 'Request.php';
            $file_path = app_path('Http/Requests/' . str_replace('\\', '/', $table['base_path']) . '/' . $file_name);
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

    }

    public function handle($json_file, \Closure $next)
    {
        $storeRequestStub = file_get_contents(__DIR__ . '/../../stubs/requests/request.store.stub');
        $updateRequestStub = file_get_contents(__DIR__ . '/../../stubs/requests/request.update.stub');

        $this->handleSingleStub($storeRequestStub, $json_file, 'Store');
        $this->handleSingleStub($updateRequestStub, $json_file, 'Update');

        return $next($json_file);
    }
}
