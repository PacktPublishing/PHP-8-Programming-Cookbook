<?php
namespace Cookbook\Commerce;

use RuntimeException;
use InvalidArgumentException;
class SecureCustomer extends Customer
{
    const ERR_CACHE = 'ERR: unable to find key cache';
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
        $data['creditCardNum'] = $this->shared->encrypt($this->creditCardNum);
        // we add another field so that we can see when serialized
        $data['timeStamp'] = date('Y-m-d H:i:s');
        // we don't want secrets stored in the serialization string
        unset($data['shared']);
        return $data;
    }
    public function __unserialize(array $data)
    {
        try {
            // retrieve the cached SharedSecrets instance
            $this->shared = unserialize(file_get_contents(SharedSecrets::KEY_CACHE_FN));
            // decrypt the CC number
            $data['creditCardNum'] = $this->shared->decrypt($data['creditCardNum']);
            // get rid of the timestamp
            unset($data['timeStamp']);
            // restore properties
            foreach ($data as $var => $value) {
                $this->$var = $value;
            }
        } catch (Throwable $t) {
            error_log(__METHOD__ . ':' . $t->getMessage());
            throw new RuntimeException(self::ERR_CACHE);
        }
    }
}
