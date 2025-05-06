<?php

namespace App\Http\Controllers\Mini;

use App\Models\MiniUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected mixed $user = null;

    public function __construct()
    {
        $token = request()->header('token');
        if ($token) $this->user = MiniUser::where('token', $token)->first();
    }
}
