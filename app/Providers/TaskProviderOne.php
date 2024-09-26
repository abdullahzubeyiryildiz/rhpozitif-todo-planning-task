<?php
namespace App\Providers;

use GuzzleHttp\Client;
use App\Contracts\TaskProviderInterface;

class TaskProviderOne implements TaskProviderInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchTasks(): array
    {
        return $this->fetchData('https://gist.githubusercontent.com/firatozpinar/8b6ac47e177f07bd99046f873154cef3/raw/b01e456f644370b1365363005c52631e182e66eb/gistfile1.txt');
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

        $data = rtrim($response->getBody()->getContents(), ",") . ']}';
        $decodedData = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON Decode Error: ' . json_last_error_msg());
        }

        return $decodedData['data'] ?? [];
    }
}
