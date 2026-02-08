<?php

namespace SMD\Common\Stripe\Api;

class Source
{
    protected $user;
    protected $sourceType;

    public function __construct($user, $sourceType = null)
    {
        $this->user = $user;
        $this->sourceType = $sourceType;
    }

    public function create($params)
    {
        // Stub - return mock source when no Stripe configured
        return ['id' => 'src_' . uniqid(), 'status' => 'chargeable'];
    }

    public function delete($sourceId)
    {
        // Stub - no-op when no Stripe configured
        return true;
    }

    public function all($limit = 100)
    {
        // Return empty collection
        return collect([]);
    }

    public static function retrieveStatic($customerId, $sourceId)
    {
        return (object)['id' => $sourceId];
    }
}
