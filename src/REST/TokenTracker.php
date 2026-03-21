<?php
namespace Cookbook\REST;
use Exception;
#[Cookbook\REST\TokenTracker(
    "See: https://platform.monica.im/docs/en/quickstart"
)]
class TokenTracker
{
    const API_ENDPOINT = 'https://api.unlikelysource.com';
    const API_KEY_FN   = __DIR__ . '/../../secure/monica_api_key.txt';
    protected static string $apiKey = '';
    public static function getApiKey()
    {
        return (empty(static::$apiKey)) ? trim(file_get_contents(static::API_KEY_FN)) : static::$apiKey;
    }
}
