<?php

namespace App\Http\Controllers\Api\Points;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointsController extends Controller
{
    public function me()
    {
        $user = auth('api')->user();
        $wallet = Wallet::where('user_id', $user->id)->where('active', 1)->first();

        return response()->json([
            'points' => $wallet ? (float) $wallet->balance : 0,
            'unit' => 'points',
            'cash_value' => null,
            'history' => DB::table('point_transactions')
                ->where('user_id', $user->id)
                ->latest()
                ->limit(30)
                ->get(),
        ]);
    }

    public function users(Request $request)
    {
        if (!auth('api')->user()->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $users = User::query()
            ->with('wallet')
            ->select(['id', 'name', 'email', 'created_at'])
            ->orderByDesc('id')
            ->paginate(25);

        return response()->json($users);
    }

    public function adjust(Request $request)
    {
        $admin = auth('api')->user();
        if (!$admin || !$admin->hasRole('admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'action' => ['required', 'in:add,deduct'],
            'amount' => ['required', 'numeric', 'gt:0', 'max:100000000'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $result = DB::transaction(function () use ($validated, $admin) {
            $wallet = Wallet::where('user_id', $validated['user_id'])
                ->where('active', 1)
                ->lockForUpdate()
                ->first();

            if (!$wallet) {
                return ['error' => 'Wallet not found'];
            }

            $before = (float) $wallet->balance;
            $amount = round((float) $validated['amount'], 4);
            $after = $validated['action'] === 'add'
                ? $before + $amount
                : $before - $amount;

            if ($after < 0) {
                return ['error' => 'Insufficient points; balance cannot be negative'];
            }

            $wallet->balance = $after;
            $wallet->save();

            $transaction = DB::table('point_transactions')->insertGetId([
                'user_id' => $wallet->user_id,
                'admin_id' => $admin->id,
                'action' => $validated['action'],
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'reason' => $validated['reason'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return [
                'transaction_id' => $transaction,
                'user_id' => $wallet->user_id,
                'points' => $after,
                'unit' => 'points',
            ];
        });

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 422);
        }

        return response()->json(['status' => true] + $result);
    }
}
