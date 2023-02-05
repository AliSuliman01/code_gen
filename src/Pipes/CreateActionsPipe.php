<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\Str;

class CreateActionsPipe
{
    private function handleSingleStub($stub, $json_file, $prefix)
    {

        foreach ($json_file['tables'] as $table) {

            $actions_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.action_namespace"];
            $models_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.model_namespace"];
            $dtos_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.dto_namespace"];

            $finalString = (new Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ action_namespace }}', $actions_namespace)
                ->replace('{{ model_namespace }}', $models_namespace)
                ->replace('{{ dto_namespace }}', $dtos_namespace)
                ->replace('{{ instance_name }}', $table['instance_name'])
                ->getString();

            if (str_starts_with($actions_namespace, 'App'))
                $actions_namespace = lcfirst($actions_namespace);

            $file_name = $prefix . $table['entity_name'] . 'Action.php';
            $file_path = base_path(str_replace('\\', '/', $actions_namespace) . '/' . $file_name);

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
        if (file_exists(base_path('stubs/code_gen/actions/action.destroy.stub')))
            $destroyActionStub = file_get_contents(base_path('stubs/code_gen/actions/action.destroy.stub'));
        else
            $destroyActionStub = file_get_contents(__DIR__ . '/../../stubs/actions/action.destroy.stub');
        
        if (file_exists(base_path('stubs/code_gen/actions/action.store.stub')))
            $storeActionStub = file_get_contents(base_path('stubs/code_gen/actions/action.store.stub'));
        else
            $storeActionStub = file_get_contents(__DIR__ . '/../../stubs/actions/action.store.stub');
        
        if (file_exists(base_path('stubs/code_gen/actions/action.update.stub')))
            $updateActionStub = file_get_contents(base_path('stubs/code_gen/actions/action.update.stub'));
        else
            $updateActionStub = file_get_contents(__DIR__ . '/../../stubs/actions/action.update.stub');

        $this->handleSingleStub($destroyActionStub, $json_file, 'Destroy');
        $this->handleSingleStub($storeActionStub, $json_file, 'Store');
        $this->handleSingleStub($updateActionStub, $json_file, 'Update');


        return $next($json_file);
    }
}
