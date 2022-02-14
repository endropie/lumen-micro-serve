<?php

namespace Endropie\LumenMicroServe\Traits;

use Endropie\LumenMicroServe\Http\Filter;

trait HasFilterable
{

    public function scopeFilter($query, Filter $filter = null)
    {
        if (!$filter) $filter = new Filter;

        return $filter->apply($query);
    }

    public function scopeCollective($query, $limit = 10)
    {
        $limit = request()->get('limit', $limit);

        return request()->has('with-limitation')
            ? $query->limitation()
            : $query->pagination();
    }

    public function scopePagination($query, $limit = 10)
    {
        $limit = request()->get('limit', $limit);

        if ($limit == '*') $limit = $query->count();

        return $query->paginate($limit);
    }

    public function scopeLimitation($query, $limit = 10)
    {
        $limit = request()->get('limit', $limit);
        $offset = request()->get('offset', 0);

        return ($limit == '*') ? $query->get()
            : $query->limit($limit)->offset($offset)->get();
    }
}
