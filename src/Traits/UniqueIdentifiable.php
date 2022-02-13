<?php

namespace Endropie\LumenMicroServe\Traits;

use Generator;
use Illuminate\Support\Str;

trait  UniqueIdentifiable
{
	public function bootUniqueIdentifiable(): void
	{
		static::creating(function ($model) {

            $model->setAttribute($model->getKeyNameUUID(), Str::uuid()->toString());

            return $model;
        });
	}

    public function getKeyNameUUID(): string
    {
        return $this->getKeyName();
    }

    public function getIncrementing()
    {
        return false;
    }
}
