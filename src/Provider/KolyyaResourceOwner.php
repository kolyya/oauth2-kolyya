<?php

namespace Kolyya\OAuth2Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class KolyyaResourceOwner implements ResourceOwnerInterface
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['username'] ?: null;
    }

    public function toArray(): array
    {
        return $this->response;
    }

    public function getEmail()
    {
        return $this->response['email'] ?: null;
    }
}
