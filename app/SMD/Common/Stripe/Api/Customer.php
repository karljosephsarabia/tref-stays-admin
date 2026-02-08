<?php

namespace SMD\Common\Stripe\Api;

class SourcesResult
{
    public function toArray()
    {
        return ['data' => []];
    }
}

class Customer
{
    protected $user;
    protected $customerId;
    protected $sourceType;

    public function __construct($user)
    {
        $this->user = $user;
        $this->customerId = $user->stripe_customer_id ?? null;
    }

    public function getDefaultSource()
    {
        // Stub - return null when no Stripe configured
        return null;
    }

    public function setDefaultSource($sourceId)
    {
        // Stub - no-op when no Stripe configured
        return true;
    }

    public function source($type)
    {
        $this->sourceType = $type;
        // Return self for chaining
        return $this;
    }

    public function all($limit = 100)
    {
        // Return an object that has toArray method
        return new SourcesResult();
    }

    public static function create($params)
    {
        return (object)['id' => 'cus_' . uniqid()];
    }

    public static function retrieve($id)
    {
        return (object)['id' => $id, 'sources' => (object)['data' => []]];
    }

    public static function update($id, $params)
    {
        return (object)['id' => $id];
    }
}
