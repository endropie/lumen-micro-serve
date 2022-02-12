<?php

namespace Endropie\LumenMicroServe\Auth\Concerns;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Concerns\HidesAttributes;
use Illuminate\Support\Traits\ForwardsCalls;

class Tokenable
{
    use HasAttributes, HidesAttributes, ForwardsCalls;

    protected $keyName = 'id';

    public function make ($key, array $array)
    {
        $this->original = array_merge($array, [$this->keyName => $key]);
        $this->setRawAttributes($this->original);

        return $this;
    }

    public function key ()
    {
        return $this->getAttribute($this->getKeyName());
    }

    protected function getKeyName (): string
    {
        return (string) $this->keyName;
    }

    public function toArray(): array
    {
        return $this->attributesToArray();
    }

    public function toJson($options = 0)
    {
        $json = json_encode($this->toArray(), $options);
        return $json;
    }

    public function usesTimestamps()
    {
        return false;
    }

    public function getCasts()
    {
        return $this->casts;
    }

    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        if (array_key_exists($key, $this->attributes) ||
            array_key_exists($key, $this->casts) ||
            $this->hasGetMutator($key) ||
            $this->hasAttributeMutator($key) ||
            $this->isClassCastable($key)) {
            return $this->getAttributeValue($key);
        }

        if (method_exists(self::class, $key)) {
            return;
        }

        return;
    }

    public function __get($key)
    {
        return $this->getAttribute($key) ?? null;
    }

    protected function getArrayableItems(array $values)
    {
        return $values;
    }
}
