<?php
namespace Cookbook\Commerce;

use InvalidArgumentException;
class SecureCustomer extends Customer
{
    public function __construct(
        public string $username,
        #[\SensitiveParameter]
        public string $creditCardNum,
        public ShipAddr $shippingAddr,
        public array $phoneNums,
        public SharedSecrets $shared) 
    {}
    public function __serialize()
    {
        $data = get_object_vars($this);
        $data['creditCardNum'] = $this->encrypt($this->creditCardNum);
        unset($data['shared']);
        return $data;
    }
    public function __unserialize(array $data)
    {
    }
    public function encrypt(string $str)
    {
        if (!in_array($this->shared::$cipher, openssl_get_cipher_methods()))
        {
            throw new InvalidArgumentException('Unsupported cipher');
        }
        return openssl_encrypt($str, $this->shared::$cipher, $this->shared::$key, 0, $this->shared::$iv, $this->shared::$tag);
    }
}
