<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Category;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public static function create($user_id = null, $actor_id = null, $type_id, $option_id = null,)
    {
        $notification                       = new Notification();
        $notification->user_id              = $user_id == null ? auth()->user()->id : $user_id;
        $notification->actor_id             = $actor_id;
        $notification->notification_type_id = $type_id;
        $notification->option_id            = $option_id;
        $notification->save();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, $created_at, Notification $notification)
    {
        if ($created_at == strtotime($notification->created_at)) {
            if ($request->read == 'Mark as read' && auth()->user()->id == $notification->user_id) {
                $notification->is_read = 1;
                $notification->save();
                return redirect()->back();
            } elseif ($request->delete == 'Delete' && auth()->user()->id == $notification->user_id) {
                $notification->id;
                $notification->delete();
                return redirect()->back();
            }
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');

        return abort(404);
    }


    public function updateStore(UpdateNotificationRequest $request, $store, $created_at, Notification $notification)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        if ($created_at == strtotime($notification->created_at)) {
            if ($request->read == 'Mark as read' && auth()->user()->id == $notification->user_id) {
                $notification->is_read = 1;
                $notification->save();
                return redirect()->back();
            } elseif ($request->delete == 'Delete' && auth()->user()->id == $notification->user_id) {
                $notification->id;
                $notification->delete();
                return redirect()->back();
            }
        }
        GeneralController::logUnauthorized($request, '404', '404 page returned');

        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }

    public function showNotifications()
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $user = auth()->user();
        return view('User.notification', [
            'user' => $user,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
        ]);
    }
}
