<?php
namespace Cookbook\Commerce;

use InvalidArgumentException;
class SharedSecrets
{
    public const CIPHER = 'aes-256-gcm';
    public const KEY_CACHE_FN = __DIR__ . '/../../secure/key_cache.txt';
    public string $iv     = '';
    public string $key    = '';
    public string $cipher = '';
    public string $tag    = '';
    public function __construct(
        string $key = NULL, 
        string $cipher = NULL)
    {
        $this->cipher = $cipher ?? self::CIPHER;
        $this->key =  $key ?? random_bytes(8);
    }
    public function encrypt(string $str)
    {
        if (!in_array(self::CIPHER, openssl_get_cipher_methods()))
        {
            throw new InvalidArgumentException('Unsupported cipher');
        }
        $this->iv = random_bytes(openssl_cipher_iv_length(self::CIPHER));
        return openssl_encrypt($str, $this->cipher, $this->key, 0, $this->iv, $this->tag);
    }
    public function decrypt(string $hash)
    {
        if (!in_array($this->cipher, openssl_get_cipher_methods()))
        {
            throw new InvalidArgumentException('Unsupported cipher');
        }
        return openssl_decrypt($hash, self::CIPHER, $this->key, 0, $this->iv, $this->tag);
    }
}
