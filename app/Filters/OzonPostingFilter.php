<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OzonPostingFilter extends QueryFilter
{
    public function search($value = null)
    {
        if (!$value) return;

        $this->builder->where(function ($query) use ($value) {
            $query->where('posting_number', 'like', '%' . $value . '%')
                ->orWhere('order_number', 'like', '%' . $value . '%')
                ->orWhere('order_id', 'like', '%' . $value . '%');
        });
    }

    public function date_from($value = null)
    {
        $this->builder->whereDate('created_at', '>=', Carbon::parse($value));
    }

    public function date_to($value = null)
    {
        $this->builder->whereDate('created_at', '<=', Carbon::parse($value));
    }

    public function sort($val = null)
    {
        if (!$val || !Str::contains($val, ','))
            return $this->builder->orderBy('created_at', 'desc');

        $sort = explode(',', $val);

        if (!in_array($sort[0], ['posting_number', 'order_id', 'order_number', 'warehouse_id', 'price', 'created_at']))
            return $this->builder->orderBy('created_at', 'desc');

        if (!in_array($sort[1], ['asc', 'desc']))
            return $this->builder->orderBy('created_at', 'desc');

        $this->builder->orderBy($sort[0], $sort[1]);
    }
}
