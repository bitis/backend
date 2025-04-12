<?php

namespace App\Http\Controllers\Financial;

use App\Models\MiniUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected mixed $user;

    public function __construct()
    {
        $this->user = MiniUser::where('token', request()->header('token'))->first();
    }
}
