<?php

declare(strict_types=1);

namespace Lichi\Vault\Sdk;

use GuzzleHttp\RequestOptions;

class Variable extends Module
{

    public function getList(string $secret): array
    {
        return $this->apiProvider->callMethod(
            "GET",
            "/v1/". $secret,
            []);
    }

    public function set(string $secret, array $variables): array
    {
        return $this->apiProvider->callMethod(
            "POST",
            "/v1/". $secret,
            [
                RequestOptions::JSON => [
                    "data" => $variables
                ]
            ]);
    }

    public function update(string $secret, array $updateVariables): array
    {
        $finalVariables = [];
        $searchedVariables = $this->getList($secret);
        $searchedVariableItems = $searchedVariables['data']['data'];
        foreach ($searchedVariableItems as $variableName => $value) {
            $finalVariables[$variableName] = $value;
        }
        foreach ($updateVariables as $variableName => $value) {
            $finalVariables[$variableName] = $value;
        }
        return $this->set($secret, $finalVariables);
    }


}