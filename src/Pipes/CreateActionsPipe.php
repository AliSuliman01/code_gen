<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Illuminate\Support\Str;

class CreateActionsPipe
{
    private function handleSingleStub($stub, $json_file, $prefix)
    {

        foreach ($json_file['tables'] as $table) {

            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $workingString = str_replace('{{ base_path }}', $table['base_path'], $workingString);
            $finalString = str_replace('{{ instance_name }}', Str::singular($table['table_name']), $workingString);

            $file_name = $prefix . $table['entity_name'] . 'Action.php';
            $file_path = app_path('Domain/' . str_replace('\\', '/', $table['base_path']) . '/Actions/' . $file_name);
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
        $destroyActionStub = file_get_contents(__DIR__ . '/../../stubs/actions/action.destroy.stub');
        $storeActionStub = file_get_contents(__DIR__ . '/../../stubs/actions/action.store.stub');
        $updateActionStub = file_get_contents(__DIR__ . '/../../stubs/actions/action.update.stub');

        $this->handleSingleStub($destroyActionStub, $json_file, 'Destroy');
        $this->handleSingleStub($storeActionStub, $json_file, 'Store');
        $this->handleSingleStub($updateActionStub, $json_file, 'Update');


        return $next($json_file);
    }
}
