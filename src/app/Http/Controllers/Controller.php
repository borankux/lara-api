<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Exceptions\ConfigurationException;
use App\Exceptions\ErrorCodes;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use JsonSchema\Validator as JsonValidator;

use App\Console\Commands\YamlToJson;
use App\Http\Responses\ApiResponse;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;
    private $apiDocs;

    protected function parseRequest(Request $request, array $parameterKeys)
    {
        $schema = $this->generateSchema($parameterKeys);

        $allParams = $request->all();
        $allParamsObject = (object)json_decode(json_encode($allParams));

        $jsonValidator = new JsonValidator();

        $jsonValidator->validate($allParamsObject, $schema);
        if (!$jsonValidator->isValid()) {
            throw new ApiException(ErrorCodes::BAD_REQUEST, json_encode($jsonValidator->getErrors()));
        }
        return $allParams;
    }



    protected function generateSchema(array $parameterKeys)
    {
        if (!$this->apiDocs) {
            $this->apiDocs = json_decode(file_get_contents(base_path(YamlToJson::API_DOC_JSON)), true);
        }
        $allParameters = $this->apiDocs['parameters'];
        $definitions = $this->apiDocs['definitions'];

        $schema = [];
        if (array_key_exists('body_name', $parameterKeys)) {
            if (array_key_exists($parameterKeys['body_name'], $allParameters)) {
                $schema = $allParameters[$parameterKeys['body_name']]['schema'];
            } else {
                throw new ConfigurationException('Invalid body_name: ' . $parameterKeys['body_name']);
            }
        }

        if (array_key_exists('query_names', $parameterKeys)) {
            foreach ($parameterKeys['query_names'] as $key => $queryName) {
                if (array_key_exists($queryName, $allParameters)) {
                    $schema['properties'][$allParameters[$queryName]['name']] = $allParameters[$queryName];
                } else {
                    throw new ConfigurationException('Invalid query_name: ' . $queryName);
                }
            }
        }

        $schema['definitions'] = $definitions;

        return $schema;
    }
}
