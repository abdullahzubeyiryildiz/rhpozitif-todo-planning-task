<?php
namespace App\Services;

use GuzzleHttp\Client;

class DeveloperService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchDevelopers()
    {
        $response = $this->client->get('https://gist.githubusercontent.com/firatozpinar/18cc10a74a98b5381d169ade6d7627d9/raw/f49c19b22412be0a380d39550d3ebd23837b637c/gistfile1.txt');

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $data['relations']['developers']['data'] ?? [];
    }
}

