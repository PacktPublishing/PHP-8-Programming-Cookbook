<?php
namespace Cookbook\Database;
use PDO;
use Exception;
use Psr\SimpleCache\CacheInterface;
#[GenAiRequest("Processes GenAI request")]
class GenAiRequest
{
    public const THRESHOLD   = 90;
    public const NO_RESPONSE = 'No response from your AI platform';
    public const AI_KEY_FN   = __DIR__ . '/../../secure/monica_api_key.txt';
    public const AI_MODEL    = 'gpt-4.1-nano';     // $0.10 / 1M input tokens | $0.40 / 1M output tokens
    public const AI_API_URL  = 'https://openapi.monica.im/v1/chat/completions';
    public function __construct(public ?CacheInterface $cache = NULL) 
    {}
    #[GenAiRequest\__invoke(
        "@param string \$request",
        "@return string \$response")]
    public function __invoke(string $request) : string
    {
        $request = trim(strip_tags($request));  // NOTE: doesn't protect against prompt injection attacks
        $text = '';
        $list = $this->cache->getMultiple([]);
        if (empty($list)) {
            // make GenAI call
            $text = $this->makeCall($request);
            // cache result
            // $this->cacheRequest($text);
        } else {
            $found = FALSE;
            while ($row = $list->fetch(PDO::FETCH_ASSOC)) {
                $percent = 0;
                similar_text($request, trim($row['request_text']), $percent);
                if ($precent >= static::THRESHOLD) {
                    // if it's inside the threshold, check to see if it's in cache
                    $text = $row['request_text'];
                    $found = TRUE;
                    break;
                }
            }
            if (!$found) {
                // make GenAI call
                $text = $this->makeCall($request);
                // cache result
                // $this->cacheRequest($text);
            }
        }
        return $text;
    }
    // curl example:
    /*
    curl https://openapi.monica.im/v1/chat/completions \
      -H "Content-Type: application/json" \
      -H "Authorization: Bearer $YOUR_API_KEY" \
      -d '{
      "model": "gpt-4o",
      "messages": [
        {
          "role": "user",
          "content": [{"type": "text", "text": "Hi!"}]
        }
      ]
    }'
    */
    public function makeCall(string $request) : string
    {
        // get API key
        $apiKey = trim(file_get_contents(static::AI_KEY_FN));
        // set up GenAI API data
        $data = [
            'model' => static::AI_MODEL,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [['type' => 'text', 'text' => $request]],
                ]
            ]
        ];
        $json = json_encode($data);
        error_log(__METHOD__ . ':' . __LINE__ . ':' . $json);
        // make the request to GenAI
        $ch = curl_init(static::AI_API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        // Set these to TRUE in production!!!
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Don't verify the peer's SSL certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // Don't verify the certificate's name against host
        // Make the call
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);        
        if (!empty($error)) {
            throw new Exception('ERROR ' . __LINE__ . ' [' . $error . ']');
        } elseif ($httpCode !== 200) {
            throw new Exception('ERROR ' . __LINE__ . ' [HTTP:' . $httpCode . ']');
        }
        $text = var_export($response, TRUE);
        if (empty($text)) {
            throw new Exception(static::NO_RESPONSE);
        }
        return $text;
    }
    protected function cacheRequest(string $text) : bool
    {
        return $this->cache->set($this->createKey($text), $text);
    }
    protected function createKey(string $text)
    {
        return md5($text);
    }
}
