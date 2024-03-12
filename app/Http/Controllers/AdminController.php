<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Dispute;
use App\Models\Escrow;
use App\Models\Featured;
use App\Models\Link;
use App\Models\MarketFunction;
use App\Models\Message;
use App\Models\Mirror;
use App\Models\News;
use App\Models\NewStore;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Pay;
use App\Models\Product;
use App\Models\Promocode;
use App\Models\Report;
use App\Models\Review;
use App\Models\Server;
use App\Models\ShareAccess;
use App\Models\Store;
use App\Models\StoreRule;
use App\Models\Support;
use App\Models\User;
use App\Models\Waiver;
use App\Models\Wallet;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{

    public function index($user, $action = null)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }

        $auth_user = auth()->user();

        // Use paginate method to retrieve paginated results
        $users = User::orderByDesc('created_at')->paginate(50);
        $stores = Store::orderByDesc('created_at')->paginate(50);
        $reports = Report::orderByDesc('created_at')->paginate(50);
        $wallets = Wallet::orderByDesc('created_at')->paginate(50);
        $products = Product::orderByDesc('created_at')->paginate(50);
        $orders = Order::orderByDesc('created_at')->paginate(50);
        $disputes = Dispute::orderByDesc('created_at')->paginate(50);
        $featureds = Featured::orderByDesc('created_at')->paginate(50);
        $new_stores = NewStore::orderByDesc('created_at')->paginate(50);
        $supports = Support::orderByDesc('created_at')->paginate(50);
        $waiver = Waiver::orderByDesc('created_at')->paginate(50);
        $escrows = Escrow::orderByDesc('updated_at')->paginate(50);
        $news = News::orderByDesc('created_at')->paginate(50);
        $carts = Cart::orderByDesc('created_at')->paginate(50);
        $categories = Category::paginate(50);
        $conversations = Conversation::orderByDesc('created_at')->paginate(50);
        $participants = Participant::orderByDesc('created_at')->paginate(50);
        $notifications = Notification::orderByDesc('created_at')->paginate(50);
        $coupons       = Promocode::orderByDesc('created_at')->paginate(50);
        $reviews      = Review::orderByDesc('created_at')->paginate(50);
        $shares      = ShareAccess::orderByDesc('created_at')->paginate(50);
        $userConversations = Participant::orderByDesc('created_at')->where('user_id', auth()->user()->id)->get();

        if ($user == $auth_user->public_name && $auth_user->role == 'admin') {
            return view('Admin.index', [
                'user' => $auth_user,
                'action' => $action,
                'icon' => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
                'userConversations'   => $userConversations,
                // Models
                'users' => $users,
                'stores' => $stores,
                'reports' => $reports,
                'reviews' => $reviews,
                'wallets' => $wallets,
                'products' => $products,
                'orders' => $orders,
                'disputes' => $disputes,
                'featureds' => $featureds,
                'new_stores' => $new_stores,
                'supports' => $supports,
                'waivers' => $waiver,
                'carts' => $carts,
                'news' => $news,
                'categories' => $categories,
                'storeConversations' => $participants,
                'conversations' => $conversations,
                'escrows' => $escrows,
                'coupons' => $coupons,
                'shares'  => $shares,
                'notifications' => $notifications,
                'dashboard_products' => Product::paginate(10)->where('status', 'Pending'),
                'dashboard_new_stores' => NewStore::paginate(10),
            ]);
        }
        return abort(403);
    }


    public function user($created_at, User $user)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();
        $show_user = $user;

        if ($created_at == strtotime($user->created_at) && $auth_user->role == 'admin') {

            return view('Admin.index', [
                'user' => $auth_user,
                'show_user' => $user,
                'action'  => 'Show User',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }



    public function new_store($created_at, NewStore $new_store)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($new_store->created_at) && $auth_user->role == 'admin') {

            return view('Admin.index', [
                'user' => $auth_user,
                'new_store' => $new_store,
                'action'  => 'New Store',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }


    public function product($created_at, Product $product)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }

        $auth_user = auth()->user();

        if ($created_at == strtotime($product->created_at) && $auth_user->role == 'admin') {

            return view('Admin.index', [
                'user' => $auth_user,
                'product' => $product,
                'action'  => 'product',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }


    // public function supportTicket($created_at, Conversation $conversation)
    // {
    //     $auth_user = auth()->user();

    //     if ($created_at == strtotime($conversation->created_at) && $auth_user->role == 'admin') {

    //         return view('Admin.ticket', [
    //             'user' => $auth_user,
    //             'conversation' => $conversation,
    //             'action'  => 'ticket',
    //             'icon'  => GeneralController::encodeImages(),
    //             'product_image' => GeneralController::encodeImages('Product_Images'),
    //             'upload_image' => GeneralController::encodeImages('Upload_Images'),
    //         ]);
    //     }
    // }

    public function joinSupport(Request  $request)
    {
        $request->validate(['support_id' => 'required|min:32']);
        $support_id = Crypt::decrypt($request->support_id);
        $support  = Support::find($support_id);

        if ($request->has('join_support')) {
            if ($support->staff_id == null) {
                $support->staff_id = auth()->user()->id;
                $support->status   = 'open';
                $support->save();

                $participant = new Participant();
                $participant->user_id = auth()->user()->id;
                $participant->conversation_id = $support->conversation_id;
                $participant->save();

                return redirect()->back();
            }
        }

        return abort(403);
    }


    public function banUnbanUser(Request $request)
    {

        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }

        if ($request->has('un_ban')) {
            $request->validate(['user_id' => 'required|min:32']);
            $user = User::find(Crypt::decrypt($request->user_id));
            $user->status = 'active';
            $user->save();
            return redirect()->back();
        } elseif ($request->has('ban')) {
            $request->validate(['user_id' => 'required|min:32']);
            $user = User::find(Crypt::decrypt($request->user_id));
            $user->status = 'banned';
            $user->save();
            return redirect()->back();
        } elseif ($request->has('review_id') && $request->has('flag_reply')) {
            $review = Review::find($request->review_id);
            $review->comment = "This review has been flagged as spam for content review. Our team is on it to maintain a quality experience. Thanks for your vigilance in keeping our platform authentic!";
            $review->save();
            return redirect()->back();
        } elseif ($request->has('senior')) {
            if (auth()->user()->role != 'admin') {
                return redirect()->back();
            }

            $request->validate(['user_id' => 'required|min:32']);
            $user = User::find(Crypt::decrypt($request->user_id));
            $user->role = "senior";
            $user->save();
            return redirect()->back();
        } elseif ($request->has('junior')) {
            if (auth()->user()->role != 'admin') {
                return redirect()->back();
            }
            $request->validate(['user_id' => 'required|min:32']);
            $user = User::find(Crypt::decrypt($request->user_id));
            $user->role = "junior";
            $user->save();
            return redirect()->back();
        } elseif ($request->has('revoke_role')) {
            if (auth()->user()->role != 'admin') {
                return redirect()->back();
            }

            $request->validate(['user_id' => 'required|min:32']);
            $user = User::find(Crypt::decrypt($request->user_id));
            $user->role = "user";
            $user->save();
            return redirect()->back();

        } elseif ($request->has('delete')) {
            if (auth()->user()->role != 'admin') {
                return redirect()->back();
            }

            $request->validate(['user_id' => 'required|min:32']);
            $user = User::find(Crypt::decrypt($request->user_id));
            $user->delete();

            return redirect()->back();
        } elseif ($request->has('reset_password')) {
            if (auth()->user()->role != 'admin') {
                return redirect()->back();
            }

            $request->validate(['user_id' => 'required|min:32']);
            $user = User::find(Crypt::decrypt($request->user_id));
            $user->password = bcrypt('user@Whales123');
            $user->save();
            return redirect()->back();
        }

        return abort(403);
    }


    public function approveDeclineStore(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }

        $request->validate(['new_store_id' => 'required|min:32']);
        $new_store = NewStore::find(Crypt::decrypt($request->new_store_id));

        if ($new_store && $request->has('approve')) {
            $store_user = User::find($new_store->user_id);
            $store_user->store_status = 'active';
            $store_user->role     = 'store';
            $store_user->save(); // Save the changes

            $store = $this->copyStore($new_store);

            $notificationType = NotificationType::where('action', 'approved')->where('icon', 'store')->first();

            if ($store && $notificationType) {
                NotificationController::create($new_store->user_id, auth()->user()->id, $notificationType->id);

                $new_store->delete();
            }

            return redirect('/');
        } elseif ($new_store && $request->has('decline')) {
            $store_user = User::find($new_store->user_id);
            $store_user->store_status = 'in_active';
            $store_user->save(); // Save the changes

            $notificationType = NotificationType::where('action', 'rejected')->where('icon', 'store')->first();

            if ($notificationType) {
                NotificationController::create($new_store->user_id, auth()->user()->id, $notificationType->id);
            }

            $new_store->delete();
            return redirect('/');
        }



        return abort(403);
    }


    private function copyStore(NewStore $newStore)
    {
        $store = new Store();
        $store->user_id    = $newStore->user_id;
        $store->store_name = $newStore->store_name;
        $store->store_description = $newStore->store_description;
        $store->store_pgp       = $newStore->user->user_pgp;
        $store->store_key   = $newStore->user->store_key;
        $store->selling     = $newStore->selling;
        $store->ship_from   =  $newStore->ship_from;
        $store->ship_to     = $newStore->ship_to;
        $store->last_updated = now();
        $store->avatar      = $newStore->avater;
        $store->save();
        return true;
    }



    public function showWaiver($user, $created_at, Waiver $waiver)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($waiver->created_at) && $auth_user->role == 'admin') {

            return view('Admin.index', [
                'user' => $auth_user,
                'waiver' => $waiver,
                'action'  => 'Waiver',
                'show_user' => $waiver->user,
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }


    public function updateWaiver(Request $request, $user, $created_at, Waiver $waiver)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($request->has('approve')) {

            $notificationType = NotificationType::where('action', 'waiver_approved')->where('icon', 'waiver')->first();

            if ($notificationType) {
                NotificationController::create($waiver->user_id, auth()->user()->id, $notificationType->id);
            }

            $notificationType = NotificationType::where('action', 'key')->where('icon', 'store')->first();
            if ($notificationType) {
                NotificationController::create($waiver->user_id, null, $notificationType->id);
            }

            $waiver->user->show_key = true;
            $waiver->user->save();

            $waiver->delete();
            return redirect('/');
        } elseif ($request->has('reject')) {
            $notificationType = NotificationType::where('action', 'waiver_rejected')->where('icon', 'store')->first();

            if ($notificationType) {
                NotificationController::create($waiver->user_id, auth()->user()->id, $notificationType->id);
                $waiver->delete();
            }

            return redirect('/');
        }
    }

    public function store($created_at, Store $store)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($store->created_at) && $auth_user->role == 'admin') {

            return view('Admin.index', [
                'user' => $auth_user,
                'store' => $store,
                'action'  => 'Store',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }


    public function order($created_at, Order $order)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($order->created_at) && $auth_user->role == 'admin') {

            return view('Admin.index', [
                'user' => $auth_user,
                'order' => $order,
                'action'  => 'Order',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }

    public function rules(Request $request)
    {
        if ($request->has('new_rule')) {
            return redirect()->back()->with('new_rule', true);
        } elseif ($request->has('rule') && $request->has('save')) {
            $request->validate(['rule' => 'required|string']);
            $rule = new StoreRule();
            $rule->name = $request->rule;
            $rule->save();
            return redirect()->back()->with('success', "Rule added successfully.");
        } elseif ($request->has('edit')) {
            $request->validate(['rule_id' => 'required|string']);
            $rule = StoreRule::find($request->rule_id);
            return redirect()->back()->with('edit_rule', true)->with('rule', $rule);
        } elseif ($request->has('delete')) {
            $request->validate(['rule_id' => 'required|string']);
            $rule = StoreRule::find($request->rule_id);
            $rule->delete();
            return redirect()->back()->with('success', "Rule deleted successfully.");
        } elseif ($request->has('save_edit')) {
            $request->validate(['rule_id' => 'required|string']);
            $rule = StoreRule::find($request->rule_id);
            $rule->name = $request->rule;
            $rule->save();
            return redirect()->back()->with('success', "Rule edited and save successfully.");
        }
    }

    public function notificationTypeEdit(Request $request)
    {

        if ($request->has('id') && $request->has('edit')) {
            $request->validate(['id' => 'required|string']);
            $notificationType = NotificationType::find($request->id);
            return redirect()->back()->with('edit', true)->with('notificationType', $notificationType);
        } elseif ($request->has('notificationType_id') && $request->has('save_edit')) {

            $request->validate(
                ['notificationType_id' => 'required|string'],
                ['name'  => 'required|string'],
                ['conetnt' => 'required|string'],
            );

            $notificationType = NotificationType::find($request->notificationType_id);
            $notificationType->name = $request->name;
            $notificationType->content = $request->content;
            $notificationType->save();

            return redirect()->back()->with('success', 'Your updates has been successfully save');
        }
    }


    public function marketFunctions(Request $request)
    {
        $request->validate(['id' => 'required|numeric']);
        $marketFunction = MarketFunction::find($request->id);
        $marketFunction->enable = $marketFunction->enable == 1 ? 0 : 1;
        $marketFunction->save();
        return redirect()->back()->with('success', 'You have updates market functions');
    }

    public function server(Request $request)
    {
        if ($request->has('new_server')) {
            return redirect()->back()->with('new_server', true);
        } elseif ($request->has('save')) {
            $server = new Server();
            $server->ip =  $request->ip;
            $server->port = $request->port;
            $server->username = $request->user_name;
            $server->password = $request->password;
            $server->type     = $request->type;
            $server->extra_user = $request->extra_user;
            $server->extra_pass = $request->extra_pass;
            $server->save();
            return redirect()->back()->with('success', 'You have successfully created server.');
        } elseif ($request->has('delete')) {
            $server = Server::find($request->id);
            $server->delete();
            return redirect()->back()->with('success', 'You have successfully deleted server.');
        } elseif ($request->has('edit')) {
            $server = Server::find($request->id);
            return redirect()->back()->with('edit_server', true)->with('server', $server);
        } elseif ($request->has('save_edit')) {
            $server = Server::find($request->id);
            $server->ip =  $request->ip;
            $server->port = $request->port;
            $server->username = $request->user_name;
            $server->password = $request->password;
            $server->type     = $request->type;
            $server->extra_user = $request->extra_user;
            $server->extra_pass = $request->extra_pass;
            $server->save();
            return redirect()->back()->with('success', 'You have successfully edited a server.');
        }
    }

    public function showMessages($user, $created_at, Conversation $conversation)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($conversation->created_at) && $auth_user->role == 'admin') {


            $messages = Message::where('conversation_id', $conversation->id)->get();
            foreach ($messages as $message) {
                $status = $message->status->where('user_id', $auth_user->id)->first(); // Use first() instead of get
                if ($status) {
                    $status->is_read = 1;
                    $status->save();
                }
            }

            return view('Admin.message', [
                'user' => $auth_user,
                'conversation' => $conversation,
                'action'  => 'message',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }

    public function supportTicket($created_at, Conversation $conversation)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($conversation->created_at) && $auth_user->role == 'admin') {

            return view('Admin.ticket', [
                'user' => $auth_user,
                'conversation' => $conversation,
                'action'  => 'ticket',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }

    public function deteleConversation(Request $request)
    {
        $conversation = Conversation::find($request->id);
        $conversation->delete();
        return redirect()->back();
    }

    public function flagReview(Request $reports, $user, Review $review)
    {
        $review->comment = "This review has been flagged as spam for content review. Our team is on it to maintain a quality experience. Thanks for your vigilance in keeping our platform authentic!";
        $review->save();

        return redirect()->back();
    }

    public function bugs(Request $request)
    {
        if ($request->has('valid')) {
            $bug = Bug::find($request->bug);
            $bug->status = 'valid';
            $bug->save();

            $notificationType = NotificationType::where('action', 'confirmed')->where('icon', 'bug')->first();

            if ($notificationType) {
                NotificationController::create($bug->user_id, auth()->user()->id, $notificationType->id);
            }

            return redirect()->back();
        } elseif ($request->has('invalid')) {
            $bug = Bug::find($request->bug);
            $bug->status = 'invalid';
            $bug->save();

            $notificationType = NotificationType::where('action', 'rejected')->where('icon', 'bug')->first();

            if ($notificationType) {
                NotificationController::create($bug->user_id, auth()->user()->id, $notificationType->id);
            }

            return redirect()->back();
        }
    }


    public function modsMessage()
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }
        $auth_user = auth()->user();
        return view('Admin.index', [
            'user' => $auth_user,
            'staffs' => User::where('role', 'admin')->orwhere('role', 'senior')->orwhere('role', 'junior')->get(),
            'action'  => 'modmail',
            'icon'  => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
        ]);
    }

    public function payout(Request $request)
    {
        if ($request->has('pay')) {
            return redirect()->back()->with('pay', true);
        }

        if ($request->has('save')) {
            $request->validate([
                'amount' => 'required|numeric|min:1|max:50000',
                'payee' => 'required',
            ]);

            $escrow = User::where('private_name', 'escrow')->first();

            if ($escrow->wallet->balance >= $request->amount) {
                $escrow->wallet->balance -= $request->amount;
                $escrow->wallet->save();

                $user = User::find($request->payee);
                $user->wallet->balance += $request->amount;
                $user->wallet->save();

                $pay = new Pay();
                $pay->user_id = $user->id;
                $pay->amount = $request->amount;
                $pay->save();

                $notificationType = NotificationType::where('action', 'confirmed')->where('icon', 'deposit')->first();
                if ($notificationType) {
                    NotificationController::create($user->id, null, $notificationType->id);
                }

                return redirect()->back()->with('seccess', "Payee sent successfully");
            } else {
                return redirect()->back()->withErrors("Escrow balance is lower than the amount.");
            }
        }
    }

    public function pay()
    {

        $auth_user = auth()->user();

        return view('Admin.index', [
            'user' => $auth_user,
            'action'  => 'payout',
            'icon'  => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
        ]);
    }

    public function mirros()
    {

        $auth_user = auth()->user();

        return view('Admin.index', [
            'user' => $auth_user,
            'action'  => 'mirrors',
            'icon'  => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
        ]);
    }

    public function mirror(Request $request)
    {
        if ($request->has('new_mirror')) {
            return redirect()->back()->with('new_mirror', true);
        } elseif ($request->has(('save'))) {
            $request->validate([
                'link' => 'required|string',
                'type' => 'required|string',
            ]);

            $mirror = new Mirror();
            $mirror->link = $request->link;
            $mirror->type = $request->type;
            $mirror->save();

            return redirect()->back();
        } elseif ($request->has('delete')) {
            $request->validate([
                'id' => 'required|string',
            ]);

            $mirror = Mirror::find($request->id);
            $mirror->delete();

            return redirect()->back();
        }
    }


    public function showStoreReviews($created_at, Store $store)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }

        if ($created_at == strtotime($store->created_at)) {
            return view(
                'Admin.storeReviews',
                [
                    'user' => auth()->user(),
                    'store' => $store,
                    'icon'  => GeneralController::encodeImages(),
                    'product_image' => GeneralController::encodeImages('Product_Images'),
                    'upload_image' => GeneralController::encodeImages('Upload_Images'),
                ]
            );
        }
    }

    public function showProductReviews($created_at, Product $product)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/whales/admin/pgp/verify');
        }

        if ($created_at == strtotime($product->created_at)) {
            return view(
                'Admin.productReviews',
                [
                    'user' => auth()->user(),
                    'product' => $product,
                    'icon'  => GeneralController::encodeImages(),
                    'product_image' => GeneralController::encodeImages('Product_Images'),
                    'upload_image' => GeneralController::encodeImages('Upload_Images'),
                ]
            );
        }
    }
}
