<?php

namespace App\Http\Controllers\Contract;

trait ValidOwner
{
    public function valid($obj)
    {
        if ($obj->user_id != auth()->id()) {
            return true;
        }
    }
}
