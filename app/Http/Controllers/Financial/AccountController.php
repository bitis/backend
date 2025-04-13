<?php

namespace App\Http\Controllers\Financial;

use App\Models\MiniCoinLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(): JsonResponse
    {
        return success($this->user);
    }

    public function coin(Request $request): JsonResponse
    {
        $coin_logs = MiniCoinLog::where('user_id', $this->user->id)
            ->when($request->input('type'), fn($query, $type) => $query->where('type', $type))
            ->orderBy('id', 'desc')
            ->paginate(getPerPage());

        return success($coin_logs);
    }
}
