<?php
namespace App\Providers;

use GuzzleHttp\Client;
use App\Contracts\TaskProviderInterface;


class TaskProviderTwo implements TaskProviderInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchTasks(): array
    {
        return $this->fetchData('https://gist.githubusercontent.com/firatozpinar/18cc10a74a98b5381d169ade6d7627d9/raw/f49c19b22412be0a380d39550d3ebd23837b637c/gistfile1.txt');
    }

    protected function fetchData(string $url): array
    {
        $response = $this->client->get($url);
        return $this->handleResponse($response);
    }

    protected function handleResponse($response): array
    {
        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON Decode Error: ' . json_last_error_msg());
        }

        return $data['data'] ?? [];
    }
}
