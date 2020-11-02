<?php

namespace App\Middleware\Api;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CanManipulateUser
{
    public function handle(Request $request, Closure $next)
    {
        $requestingUser = $request->user();
        $targetUserId = $request->route('userId');
        $targetUser = User::with('createdByOperator')->findOrFail($targetUserId);

        if ($requestingUser->isPeasant()) {
            if ($targetUserId !== $requestingUser->getId()) {
                Log::alert('User with ID: ' . $requestingUser->getId() .
                    ' tried to manipulate user with ID ' . $targetUserId );
            }

            return response()->json('Forbidden', 401);
        } elseif ($requestingUser->isEditor()) {
            if (!$targetUser->createdByOperator) {
                return response()->json('You cannot manipulate users', 401);
            }

            if ($targetUser->createdByOperator->getId() !== $requestingUser->getId()) {
                return response()->json('You cannot manipulate bots that you have not created', 401);
            }
        }

        return $next($request);
    }
}
