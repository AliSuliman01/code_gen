<?php


namespace Alisuliman\CodeGen\Pipes;


use Illuminate\Support\Str;

class CreateViewModelsPipe
{
    private function handleSingleStub($stub, $json_file, $prefix)
    {

        foreach ($json_file['tables'] as $table) {

            $workingString = str_replace('{{ entity_name }}', $table['entity_name'], $stub);
            $workingString = str_replace('{{ base_path }}', $table['base_path'], $workingString);
            $finalString = str_replace('{{ instance_name }}', Str::singular($table['table_name']), $workingString);

            $file_name = $prefix . $table['entity_name'] . 'VM.php';
            $file_path = app_path('Http/ViewModels/' . str_replace('\\', '/', $table['base_path']) . '/' . $file_name);
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
        $getAllViewModelStub = file_get_contents(__DIR__ . '/../../stubs/vm/vm.get_all.stub');
        $getViewModelStub = file_get_contents(__DIR__ . '/../../stubs/vm/vm.get.stub');

        $this->handleSingleStub($getAllViewModelStub, $json_file, 'GetAll');
        $this->handleSingleStub($getViewModelStub, $json_file, 'Get');

        return $next($json_file);
    }
}
