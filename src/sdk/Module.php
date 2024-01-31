<?php

declare(strict_types=1);


namespace Lichi\Vault\Sdk;


use Lichi\Vault\ApiProvider;

class Module
{

    /**
     * @var ApiProvider
     */
    protected ApiProvider $apiProvider;

    public function __construct(ApiProvider $provider)
    {
        $this->apiProvider = $provider;
    }

}