<?php
namespace App\Factories;

use App\Contracts\TaskProviderInterface;
use App\Providers\TaskProviderOne;
use App\Providers\TaskProviderTwo;

class TaskProviderFactory
{
    public static function create(string $providerType): TaskProviderInterface
    {
        switch ($providerType) {
            case 'provider_one':
                return new TaskProviderOne(new \GuzzleHttp\Client());
            case 'provider_two':
                return new TaskProviderTwo(new \GuzzleHttp\Client());
            default:
                throw new \InvalidArgumentException("Unknown provider type: {$providerType}");
        }
    }
}
