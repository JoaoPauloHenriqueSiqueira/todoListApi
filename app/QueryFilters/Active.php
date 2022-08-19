<?php

namespace App\QueryFilters;

class Active
{
    public function handle($request, \Closure $next)
    {
        if(!request()->has('active')){
            return $next($request);
        }

        $builder = $next($request);
        return $builder->where('active', request('active'));
    }

}
