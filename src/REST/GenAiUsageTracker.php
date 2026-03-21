<?php
namespace Cookbook\REST;
use PDO;
use Exception;
use DateInterval;
use Psr\Container\ContainerInterface;
#[GenAiUsageTracker("Tracks GenAI token usage")]
class GenAiUsageTracker
{
    public function __construct(ContainerInterface $container)
    {
        $this->ai_config = $container->get('ai_config');
    }
    #[GenAiRequest\__invoke(
        "@param string \$request",
        "@return string \$response")]
    public function __invoke(string $request) : string
    {
        // get API key
        $apiKey = trim(file_get_contents($this->ai_config['AI_KEY_FN']));
        // set up GenAI API data
        $data = [
            'model' => $this->ai_config['AI_MODEL'],
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [['type' => 'text', 'text' => $this->ai_config['AI_SYS_TEXT'] . ' ' . $request]],
                ]
            ]
        ];
        $json = json_encode($data);
        // make the request to GenAI
        $ch = curl_init($this->ai_config['AI_API_URL']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
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
    
        // track usage
        
        // handle errors
        if (!empty($error)) {
            throw new Exception('ERROR ' . __LINE__ . ' [' . $error . ']');
        }
        
        if ($httpCode !== 200) {
            throw new Exception('ERROR ' . __LINE__ . ' [HTTP:' . $httpCode . '] ');
        }
        $text = json_decode($response, TRUE)['choices'][0]['message']['content'] ?? '';
        if (empty($text)) {
            throw new Exception(static::NO_RESPONSE);
        }
        return $text;
    }
    protected function createKey(string $text)
    {
        return md5($text);
    }
}
