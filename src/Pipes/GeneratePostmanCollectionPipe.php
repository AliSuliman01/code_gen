<?php


namespace Alisuliman\CodeGenerator\Pipes;


class GeneratePostmanCollectionPipe
{
    private function get_json_raw($columns){
        $json_raw = '';
        foreach ($columns as $column){

            $json_raw .= (strlen($json_raw) == 0 ? '{':',').'\r\n           \"'.$column['name'].'\": \"\"';

        }
        return $json_raw.'\r\n        }';
    }

    public function handle($json_file, \Closure $next)
    {

        $collectionArray = json_decode(file_get_contents(__DIR__ . '/../../stubs/postman/collection.json'), true);
        $collectionItemStub = file_get_contents(__DIR__ . '/../../stubs/postman/collection_item.json');
        $fileContent = json_decode(file_get_contents(base_path('postman_collection.json')), true);

        $stringCollectionItems = '';

        foreach ($json_file['tables'] as $table) {
            $stringCollectionItem = str_replace('{{ table_name }}', $table['table_name'], $collectionItemStub);
            $stringCollectionItem = str_replace('{{ postman_json_raw }}', $this->get_json_raw($table['columns']), $stringCollectionItem);
            $stringCollectionItems .= (strlen($stringCollectionItems) == 0 ? '':',').$stringCollectionItem;
        }
        $arrayCollectionItems = json_decode($stringCollectionItems,true);
        if (isset($fileContent['item'])){
            $arrayCollectionItems = array_merge($fileContent['item'], $arrayCollectionItems);
        }

        $collectionArray['item'] = $arrayCollectionItems;
        $file = fopen(base_path('postman_collection.json'), 'w');
        fwrite($file, json_encode($collectionArray,JSON_UNESCAPED_SLASHES));
        fclose($file);

        echo "\e[0;32m postman_collection.json Generated.\e[0m\n";

        return $next($json_file);
    }
}
