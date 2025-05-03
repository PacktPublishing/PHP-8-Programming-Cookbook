<?php
namespace Cookbook\Commerce;

class SharedSecrets
{
    public static string $iv     = '';
    public static string $key    = '';
    public static string $cipher = '';
    public static string $tag    = '';
    public const CIPHER = 'aes-256-gcm';
    public function __construct(
        string $key = NULL, 
        string $cipher = NULL)
    {
        self::$key =  $key ?? random_bytes(8);
        self::$cipher = $cipher ?? self::CIPHER;
        self::$iv = random_bytes(openssl_cipher_iv_length(self::$cipher));
    }
}
