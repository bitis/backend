<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{

    use AuthorizesRequests, ValidatesRequests;

    protected mixed $user;

    protected mixed $store_id;

    protected mixed $operator_id;

    public function __construct()
    {
        $this->user = request()->user();
        $this->store_id = $this->user?->store_id;
        $this->operator_id = $this->user?->id;
    }

    protected function store()
    {
        return $this->user?->store;
    }
}
