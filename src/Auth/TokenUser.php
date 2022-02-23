<?php

namespace Endropie\LumenMicroServe\Auth;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;

class TokenUser extends GenericUser implements Authenticatable
{

    public function toArray()
    {
        return (array) $this->attributes;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }
    
    public function __toString()
    {
        return $this->toJson();
    }
}