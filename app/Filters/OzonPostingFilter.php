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
}
