<?php

namespace App\Http\Middleware;

use App\Http\Controllers\GeneralController;
use App\Models\Address;
use App\Models\Conversation;
use App\Models\Dispute;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Support;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CleanupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Delete 'message' type messages older than 10 days
        Message::where('message_type', '=', 'message')
            ->where('created_at', '<', now()->subDays(15))
            ->delete();

        // Delete in active conversation older than 15 days
        Conversation::whereHas('messages', function ($query) {
            $query->where('created_at', '<', now()->subDays(15));
        })->delete();

        // Delete closed dispute orders older than 15 days
Order::whereHas('dispute', function ($userQuery) {
    $userQuery->where('status', 'closed')
              ->where('updated_at', '<', now()->subDays(15));
})->delete();

        // Delete closed disputes older than 15 days
        Dispute::where('status', '=', 'closed')
            ->where('updated_at', '<', now()->subDays(15))
            ->delete();

        // Delete closed support tickets older than 15 days
        Support::where('status', '=', 'closed')
            ->where('updated_at', '<', now()->subDays(15))
            ->delete();

        // Delete cancelled and completed orders older than 15 days
        Order::where(function ($query) {
            $query->where('status', '=', 'cancelled')
                ->orWhere('status', '=', 'completed');
        })
            ->where('updated_at', '<', now()->subDays(15))
            ->delete();


        // Assuming you have a 'participants' relationship in your Conversation model
        Conversation::whereHas('participants', function ($query) {
            $query->where('is_hidden', '=', 1);
        }, '=', Participant::count())
            ->delete();


        // Delete notifications older than 30 days
        Notification::where('created_at', '<', now()->subDays(30))->delete();

        // Update addresses that were created more than one hour ago
        DB::table('addresses')
            ->where('created_at', '<', now()->subHours(1))
            ->update(['expired' => 1]);


        if (Auth::check()) {
            Auth::user()->updateLastSeen();
            Order::cancelPendingOrdersOlderThan72Hours();

            Order::completeSentDeliveredOrdersOlderThan72Hours();
            User::AutoVacation();
        }

        if (Auth::check()) {
            $user = Auth::user();
           $unauthorize =  $user->unauthorizes->count();

        //    if ($unauthorize == 0) {
        //     return $next(404);
        //    }
        }
        return $next($request);
    }
}
