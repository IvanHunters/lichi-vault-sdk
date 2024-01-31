<?php


namespace Lichi\Vault;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Lichi\Vault\Sdk\Variable;
use RuntimeException;

class ApiProvider
{
    private Client $client;
    private string $token;

    /**
     * ApiProvider constructor.
     * @param Client $client
     * @param string $token
     */
    public function __construct(Client $client, string $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param string $typeRequest
     * @param string $method
     * @param array $params
     * @param bool $needAuth
     * @return mixed
     */
    public function callMethod(string $typeRequest, string $method, array $params = [], bool $needAuth = true)
    {
        usleep(380000);
        try {
            if ($needAuth) {
                $params[RequestOptions::HEADERS]['X-Vault-Token'] = $this->token;
            }
            $response = $this->client->request($typeRequest, $method, $params);
        } catch (GuzzleException $exception){
            try {
                $response = $exception->getResponse()->getBody(true);
            } catch (\Throwable $e) {
                throw new RuntimeException(sprintf(
                    "API ERROR, Method: %s\nParams: %s",
                    $method,
                    json_encode($params, JSON_UNESCAPED_UNICODE)
                ));
            }

            throw new RuntimeException(sprintf(
                "API ERROR, Method: %s\nParams: %s\nResponse: %s",
                $method,
                json_encode($params, JSON_UNESCAPED_UNICODE),
                $response,
            ));
        }

        if ($response->getStatusCode() != 200) {
            throw new RuntimeException(sprintf(
                "Http status code not 200, got %s status code, message: %s",
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }

        /** @var string $content */
        $content = $response->getBody()->getContents();

        /** @var array $response */
        $response = json_decode($content, true);

        return $response;
    }

    public function variables(): Variable
    {
        $self = clone $this;
        return new Variable($self);
    }

}