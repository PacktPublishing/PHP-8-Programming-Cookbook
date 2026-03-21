<?php
namespace Cookbook\REST;
use Exception;
use SplFileObject;
class SimpleAPICall
{
    const API_GET_URL = 'https://api.unlikelysource.com/api';
    const API_POST_URL = 'https://api.unlikelysource.com/post';
    const API_ERROR = 'ERROR: api transmission error';
    const NUM_BYTES = 4096;
    public static function send(array $data, EnumMethod $method, EnumExt $ext)
    {
        return match (TRUE) {
            ($method === EnumMethod::GET && $ext === EnumExt::STREAMS) => static::streamsGet($data),
            ($method === EnumMethod::GET && $ext === EnumExt::CURL) => static::streamsGet($data),
            ($method === EnumMethod::POST && $ext === EnumExt::STREAMS) => static::streamsPost($data),
            ($method === EnumMethod::POST && $ext === EnumExt::CURL) => static::streamsPost($data),
            default => static::streamsGet($data)
        };
    }
    public static function streamsGet(array $data) : string
    {
        return (string) file_get_contents(static::API_GET_URL . '?' . http_build_query($data));
    }
    public static function curlGet(array $data) : string
    {
        $ch = curl_init(static::API_ENDPOINT . '/api/?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set these to TRUE in production!!!
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Don't verify the peer's SSL certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // Don't verify the certificate's name against host
        // Make the call
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        if (!empty($error)) {
            throw new Exception(sprintf('ERROR %s [%s]', __LINE__, $error));
        }
        return $response;
    }
    public static function streamsPost(array $data) : string
    {
        $send = http_build_query($data);
        $headers = [
            'Content-type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($send)
        ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header'=> implode("\r\n", $headers),
                'content' => $send
            ]
        ];
        $context = stream_context_create($options);
        $fp = new SplFileObject(static::API_POST_URL, 'r', false, $context);
        $response = [];
        try {
            $text = $fp->fread(static::NUM_BYTES);
            while (!$fp->eof()) {
                $text .= $fp->fread(static::NUM_BYTES);
            }
            $response = ['success' => TRUE, 'data' => $text];
        } catch (Throwable $t) {
            error_log(__METHOD__ . ':' . $t->getMessage());
            $response = ['success' => FALSE, 'data' => static::API_ERROR];
        }
        return json_encode($response, JSON_PRETTY_PRINT);
    }
    public static function curlPost(array $data) : string
    {
        $send = http_build_query($data);
        $ch = curl_init(static::API_ENDPOINT);
        curl_setopt_array($ch, [
            CURLOPT_POST            => true,
            CURLOPT_HTTPHEADER      => [
                'Content-type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($send),
            ],
            CURLOPT_POSTFIELDS      => $send,
            CURLOPT_RETURNTRANSFER  => true,   // return body as string (binary-safe)
            CURLOPT_HEADER          => true,   // include headers so we can split + read status
            CURLOPT_TIMEOUT         => 60,
            CURLOPT_SSL_VERIFYPEER  => false,   // set this to TRUE in production!
            CURLOPT_SSL_VERIFYHOST  => false    // set this to TRUE in production!
        ]);
        try {
            $text = curl_exec($ch);
            $response = ['success' => TRUE, 'data' => $text];
        } catch (Throwable $t) {
            error_log(__METHOD__ . ':' . $t->getMessage());
            $response = ['success' => FALSE, 'data' => static::API_ERROR];
        } finally {
            curl_close($ch);
        }      
        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
