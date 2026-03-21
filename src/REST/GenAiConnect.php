<?php
namespace Cookbook\REST;
use Exception
class GenAiConnect
{
    public function __construct(protected array $config)
    {}
    public function genAIcall(string $prompt) : string
    {
        $config = $this->config['ai_config'];
        // $config is an array that contains the following keys:
        /*
         * api_endpoint : endpoint for the API call
         * api_model    : model to use
         * api_key      : API key
         * api_opts     : an array of additional options for the chosen AI platform
         */
        $data = array_merge([
            'model'    => $config['api_model'],
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ], $config['api_opts'] ?? []);
        $json = json_encode($data);
        $ch = curl_init($config['api_endpoint']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $config['api_key'],
            ],
            CURLOPT_SSL_VERIFYPEER => false,  // Set to TRUE in production!
            CURLOPT_SSL_VERIFYHOST => false,  // Set to TRUE in production!
        ]);
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);
        if (!empty($error)) {
            throw new \Exception(sprintf('ERROR %s [%s]', __LINE__, $error));
        }
        if ($httpCode !== 200) {
            throw new \Exception(sprintf('ERROR %s [HTTP:%s]', __LINE__, $httpCode));
        }
        $response = (string) $result;
        return $response;
    }
}
