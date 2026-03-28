<?php
namespace Cookbook\REST;
use Exception;
class GenAiConnect
{
    public const ERR_TRANS = 'Unknown transmission error.';
    public const CALL_LOG  = __DIR__ . '/../Chapter07/api_call.log';
    public function __construct(protected array $config)
    {}
    public function genAIcall(string $prompt) : string
    {
        // $config is an array that contains the following keys:
        /*
         * ai_api_url : endpoint for the API call
         * ai_model   : model to use
         * ai_api_key : API key
         * ai_opts    : an array of additional options for the chosen AI platform
         */
        $data = array_merge([
            'model'    => $this->config['ai_model'],
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ], $this->config['ai_opts'] ?? []);
        $json = json_encode($data);
        $ch = curl_init($this->config['ai_api_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $json,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->config['ai_api_key'],
            ],
            CURLOPT_SSL_VERIFYPEER => false,  // Set to TRUE in production!
            CURLOPT_SSL_VERIFYHOST => false,  // Set to TRUE in production!
        ]);
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);
        // method, timestamp, $result
        $message  = sprintf('%s|%d|%s' . PHP_EOL, 
                            __METHOD__, time(), $result);
        file_put_contents(static::CALL_LOG, $message, FILE_APPEND);
        if (!empty($error)) {
            error_log(__METHOD__ . ':' . $error);
            throw new Exception(sprintf('%s [%s]', static::ERR_TRANS, __LINE__));
        }
        if ($httpCode !== 200) {
            throw new Exception(sprintf('ERROR %s [HTTP:%s]', __LINE__, $httpCode));
        }
        $response = (string) $result;
        return $response;
    }
    public function __invoke(string $prompt)
    {
        return $this->genAiCall($prompt);
    }
}
