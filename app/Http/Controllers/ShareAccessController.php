<?php

namespace App\Http\Controllers;

use App\Models\ShareAccess;
use App\Http\Requests\StoreShareAccessRequest;
use App\Http\Requests\UpdateShareAccessRequest;
use App\Models\SharePermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ShareAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        if ($request->has('new_share_access')) {
            if (!auth()->user()->store->is_verified) {
        GeneralController::logUnauthorized($request, 'Not verify', 'not veryfy for share access');
                return redirect()->back()->withErrors('Your are not verified to access this feature!!!');
            }
            return redirect()->back()->with('new_share_access', true);
        }

        if ($request->has('create_access')) {
            return $this->createNewAccess($request);
        }

        if ($request->has('revoke_yes')) {
            $request->validate(['user' => 'required|string|min:32']);
            $store = auth()->user()->store;
            $user_id = Crypt::decrypt($request->user);
            $share = $store->share->where('user_id', $user_id)->first();

            if ($share == null) {
                return redirect()->withErrors('This user doesnt exist.');
            }

            $share->user->role = 'user';
            $share->user->save();
            $share->delete();

            return redirect()->back()->with('success', 'You have successfully revoke user share access.');
        }

        if ($request->has('revoke_no')) {
            return redirect()->back();
        }

        if ($request->has('revoke')) {
            return redirect()->back()->with('user_id', $request->user)->with('revoke', true);
        }

        if ($request->has('edit')) {
            $request->validate(['user' => 'required|string|min:32']);
            $store = auth()->user()->store;
            $user_id = Crypt::decrypt($request->user);
            $share = $store->share->where('user_id', $user_id)->first();

            if ($share == null) {
                return redirect()->withErrors('This user doesnt exist.');
            }

            return redirect()->back()->with('share', $share)->with('edit', true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShareAccessRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShareAccess $shareAccess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShareAccess $shareAccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShareAccessRequest $request, ShareAccess $shareAccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShareAccess $shareAccess)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    private function createNewAccess(Request $request)
    {
        $request->validate([
            "dashboard" => 'sometimes|in:on',
            "add_listing" => 'sometimes|in:on',
            "products" => 'sometimes|in:on',
            "reviews" => 'sometimes|in:on',
            "orders" => 'sometimes|in:on',
            "store_stats" => 'sometimes|in:on',
            "affiliate" => 'sometimes|in:on',
            "promotion" => 'sometimes|in:on',
            "coupons" => 'sometimes|in:on',
            "support" => 'sometimes|in:on',
            "news" => 'sometimes|in:on',
            "rules" => 'sometimes|in:on',
            "private_name" => 'required|string',
        ]);

        $person = User::where('private_name', $request->private_name)->first();
        $user = auth()->user();

        if ($person == null) {
            return redirect()->back()->withErrors('User with this provided name doesnt exist.');
        }

        $allshares = ShareAccess::where('user_id', $person->id)->first();

        if ($allshares != null) {
            return redirect()->back()->withErrors('This user already has a share access.');
        }

        // add the new share access...
        $access = new ShareAccess();
        $access->store_id = $user->store->id;
        $access->user_id = $person->id;
        $access->save();

        // change the user role to share access...
        $access->user->role = 'share';
        $access->user->save();

        $this->createPermissions($request, $access->id);


        return redirect()->back();
    }

    private function createPermissions($request, $access)
    {
        $permissions = [
            'dashboard',
            'add_listing',
            'products',
            'reviews',
            'orders',
            'store_stats',
            'affiliate',
            'promotion',
            'coupons',
            'support',
            'news',
            'rules',
        ];

        foreach ($permissions as $permission) {
            if ($request->has($permission)) {
                $newPermission = new SharePermission();
                $newPermission->share_access_id = $access;
                $newPermission->name = ucfirst(str_replace('_', ' ', $permission));
                $newPermission->save();
            }
        }
    }
}
