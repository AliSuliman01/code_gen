<?php


namespace Alisuliman\CodeGenerator\Pipes;


use Alisuliman\CodeGenerator\Helpers\PipeData;
use Alisuliman\CodeGenerator\Helpers\Str;

class GeneratePostmanCollectionPipe implements CodeGenPipContract
{
    private function get_json_raw($columns)
    {
        $json_raw = '';
        foreach ($columns as $column) {

            $json_raw .= (strlen($json_raw) == 0 ? '{' : ',') . '\r\n           \"' . $column['name'] . '\": \"\"';

        }
        return $json_raw . '\r\n        }';
    }

    public function handle(PipeData $pipeData, \Closure $next)
    {
        if (file_exists(base_path('stubs/code_gen/postman/collection.json')))
            $collectionArray = json_decode(file_get_contents(base_path('stubs/code_gen/postman/collection.json')), true);
        else
            $collectionArray = json_decode(file_get_contents(__DIR__ . '/../../stubs/postman/collection.json'), true);

        if (file_exists(base_path('stubs/code_gen/postman/collection_item.json')))
            $collectionItemStub = file_get_contents(base_path('stubs/code_gen/postman/collection_item.json'));
        else
            $collectionItemStub = file_get_contents(__DIR__ . '/../../stubs/postman/collection_item.json');


        if (file_exists(base_path('postman_collection.json')))
        $fileContent = json_decode(file_get_contents(base_path('postman_collection.json')), true);

        $postmanBaseUrl = config('code_gen.postman.base_url');
        
        $stringCollectionItems = '';

        foreach ($pipeData->json_file['tables'] as $table) {

            $stringCollectionItem = (new Str($collectionItemStub))
                ->replace('{{ table_name }}', $table['table_name'])
                ->replace('{{ base_url }}', $postmanBaseUrl)
                ->replace('{{ postman_json_raw }}', $this->get_json_raw($table['columns']))
                ->getString();

            $stringCollectionItems .= (strlen($stringCollectionItems) == 0 ? '' : ',') . $stringCollectionItem;
        }

        $stringCollectionItems = "[$stringCollectionItems]";
        $arrayCollectionItems = json_decode($stringCollectionItems, true);
        if (isset($fileContent['item'])) {
            $arrayCollectionItems = array_merge($fileContent['item'], $arrayCollectionItems);
        }

        $collectionArray['item'] = $arrayCollectionItems;
        $file = fopen(base_path('postman_collection.json'), 'w');
        fwrite($file, json_encode($collectionArray, JSON_UNESCAPED_SLASHES));
        fclose($file);

        echo "\e[0;32m postman_collection.json Generated.\e[0m\n";

        return $next($pipeData);
    }
}
