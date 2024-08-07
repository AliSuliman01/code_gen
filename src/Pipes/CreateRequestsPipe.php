<?php


namespace Alisuliman\CodeGenerator\Pipes;

use Alisuliman\CodeGenerator\Helpers\PipeData;

class CreateRequestsPipe implements CodeGenPipContract
{
    private $base_form_request_class;
    private $base_form_request_class_name;

    public function __construct()
    {
        $this->base_form_request_class = config('code_gen.base_form_request');
        $this->base_form_request_class_name = class_basename($this->base_form_request_class);
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

            $request_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.request_namespace"];

            $finalString = (new \Alisuliman\CodeGenerator\Helpers\Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ request_namespace }}', $request_namespace)
                ->replace('{{ base_form_request_class }}', $this->base_form_request_class)
                ->replace('{{ base_form_request_class_name }}', $this->base_form_request_class_name)
                ->replace('{{ request_rules }}', $this->prepareRequestRules($table['columns']))
                ->getString();

            if (str_starts_with($request_namespace, 'App'))
                $request_namespace = lcfirst($request_namespace);

            $file_name = $prefix . $table['entity_name'] . 'Request.php';
            $file_path = base_path(str_replace('\\', '/', $request_namespace). '/' . $file_name);

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

    public function handle(PipeData $pipeData, \Closure $next)
    {
        if (file_exists(base_path('stubs/code_gen/requests/request.store.stub')))
            $storeRequestStub = file_get_contents(base_path('stubs/code_gen/requests/request.store.stub'));
        else
            $storeRequestStub = file_get_contents(__DIR__ . '/../../stubs/requests/request.store.stub');

        if (file_exists(base_path('stubs/code_gen/requests/request.update.stub')))
            $updateRequestStub = file_get_contents(base_path('stubs/code_gen/requests/request.update.stub'));
        else
            $updateRequestStub = file_get_contents(__DIR__ . '/../../stubs/requests/request.update.stub');

        $this->handleSingleStub($storeRequestStub, $pipeData->json_file, 'Store');
        $this->handleSingleStub($updateRequestStub, $pipeData->json_file, 'Update');

        return $next($pipeData);
    }
}
