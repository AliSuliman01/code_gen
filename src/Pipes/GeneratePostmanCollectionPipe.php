<?php


namespace Alisuliman\CodeGenerator\Pipes;


class GeneratePostmanCollectionPipe
{
    private function get_json_raw($columns){
        $json_raw = '';
        foreach ($columns as $column){

            $json_raw .= (strlen($json_raw) == 0 ? '{':',')."\r\n           \"{$column['name']}\": \"\"";

        }
        return "$json_raw\r\n        }";
    }

    public function handle($json_file, \Closure $next)
    {

        $collectionStub = file_get_contents(__DIR__ . '/../../stubs/postman/collection.stub');
        $collectionItemStub = file_get_contents(__DIR__ . '/../../stubs/postman/collection_item.stub');

        $collectionItems = '';
        foreach ($json_file['tables'] as $table) {
            $collectionItem = str_replace('{{ table_name }}', $table['table_name'], $collectionItemStub);
            $postman_json_raw = $this->get_json_raw($table['columns']);
            $collectionItem = str_replace('{{ postman_json_raw }}', $postman_json_raw, $collectionItem);
            $collectionItems .= (strlen($collectionItems) == 0 ? '':',').$collectionItem;
        }
        $collection = str_replace('{{ collection_items }}', "[$collectionItems]", $collectionStub);

        $file = fopen(base_path('postman_collection.json'), 'w');
        fwrite($file, $collection);
        fclose($file);   
        return $next($json_file);
    }
}
