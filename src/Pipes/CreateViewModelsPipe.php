<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\PipeData;
use Illuminate\Support\Str;

class CreateViewModelsPipe implements CodeGenPipContract
{
    private function handleSingleStub($stub, $json_file, $prefix, $suffix)
    {
        foreach ($json_file['tables'] as $table) {

            $model_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.model_namespace"];
            $vm_namespace = GenerateNamespacesArrayPipe::$namespaces["{$table['table_name']}.vm_namespace"];

            $finalString = (new \Alisuliman\CodeGenerator\Helpers\Str($stub))
                ->replace('{{ entity_name }}', $table['entity_name'])
                ->replace('{{ model_namespace }}', $model_namespace)
                ->replace('{{ vm_namespace }}', $vm_namespace)
                ->replace('{{ instance_name }}', $table['instance_name'])
                ->getString();

            if (str_starts_with($vm_namespace, 'App'))
                $vm_namespace = lcfirst($vm_namespace);

            $file_name = $prefix . $table['entity_name'] . $suffix . 'VM.php';
            $file_path = base_path(str_replace('\\', '/', $vm_namespace) . '/' . $file_name);

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
        if (file_exists(base_path('stubs/code_gen/vm/vm.get_all.stub')))
            $getAllViewModelStub = file_get_contents(base_path('stubs/code_gen/vm/vm.get_all.stub'));
        else
            $getAllViewModelStub = file_get_contents(__DIR__ . '/../../stubs/vm/vm.get_all.stub');

        if (file_exists(base_path('stubs/code_gen/vm/vm.get.stub')))
            $getViewModelStub = file_get_contents(base_path('stubs/code_gen/vm/vm.get.stub'));
        else
            $getViewModelStub = file_get_contents(__DIR__ . '/../../stubs/vm/vm.get.stub');

        $this->handleSingleStub($getAllViewModelStub, $pipeData->json_file, 'GetAll', 's');
        $this->handleSingleStub($getViewModelStub, $pipeData->json_file, 'Get', '');

        return $next($pipeData);
    }
}
