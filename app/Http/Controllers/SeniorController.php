<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Dispute;
use App\Models\FAQ;
use App\Models\Featured;
use App\Models\MarketKey;
use App\Models\Message;
use App\Models\MessageStatus;
use App\Models\News;
use App\Models\NewStore;
use App\Models\Notification;
use App\Models\NotificationType;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Product;
use App\Models\Report;
use App\Models\Review;
use App\Models\Store;
use App\Models\Support;
use App\Models\Unauthorize;
use App\Models\User;
use App\Models\Waiver;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class SeniorController extends Controller
{


    public function index($user, $action = null)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        // Use paginate method to retrieve paginated results
        $users = User::paginate(50);
        $stores = Store::paginate(50);
        $reports = Report::paginate(50);
        $wallets = Wallet::paginate(50);
        $products = Product::paginate(50);
        $orders = Order::paginate(50);
        $disputes = Dispute::paginate(50);
        $featureds = Featured::paginate(50);
        $new_stores = NewStore::paginate(50);
        $supports = Support::paginate(50);
        $waiver = Waiver::paginate(50);
        $news = News::paginate(50);
        $categories = Category::paginate(50);
        $conversations = Conversation::paginate(50);
        $participants = Participant::paginate(50);
        $notifications = Notification::paginate(50);
        $faqs           = FAQ::all();
        $userConversations = Participant::where('user_id', auth()->user()->id)->get();

        if ($user == $auth_user->public_name && $auth_user->role == 'senior') {
            return view('Senior.index', [
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
                'wallets' => $wallets,
                'products' => $products,
                'orders' => $orders,
                'disputes' => $disputes,
                'featureds' => $featureds,
                'new_stores' => $new_stores,
                'supports' => $supports,
                'waivers' => $waiver,
                'news' => $news,
                'categories' => $categories,
                'storeConversations' => $participants,
                'conversations' => $conversations,
                'notifications' => $notifications,
                'faqs'          => $faqs,
                'dashboard_products' => Product::where('status', 'pending')->paginate(10),
                'dashboard_new_stores' => NewStore::paginate(10),
            ]);
        }
        return abort(404);
    }


    public function user($created_at, User $user)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        $auth_user = auth()->user();

        if ($created_at == strtotime($user->created_at) && $auth_user->role == 'senior') {

            return view('Senior.index', [
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
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($new_store->created_at) && $auth_user->role == 'senior') {

            return view('Senior.index', [
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
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($product->created_at) && $auth_user->role == 'senior') {

            return view('Senior.index', [
                'user' => $auth_user,
                'product' => $product,
                'action'  => 'product',
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
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($conversation->created_at) && $auth_user->role == 'senior') {

            return view('Senior.ticket', [
                'user' => $auth_user,
                'conversation' => $conversation,
                'action'  => 'ticket',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }

    public function showMessages($user, $created_at, Conversation $conversation)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($conversation->created_at) && $auth_user->role == 'senior') {


            $messages = Message::where('conversation_id', $conversation->id)->get();
            foreach ($messages as $message) {
                $status = $message->status->where('user_id', $auth_user->id)->first(); // Use first() instead of get
                if ($status) {
                    $status->is_read = 1;
                    $status->save();
                }
            }

            return view('Senior.displayMessages', [
                'user' => $auth_user,
                'conversation' => $conversation,
                'action'  => 'message',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
       }
    }

    public function joinSupport(Request  $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
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
            return redirect('/auth/staff/senior/pgp/verify');
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
        }

        return abort(403);
    }

    public function approveDeclineStore(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
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

            return redirect()->back('/');
        }



        return abort(403);
    }


    private function copyStore(NewStore $newStore)
    {
        $store = new Store();
        $store->user_id    = $newStore->user_id;
        $store->store_name = $newStore->store_name;
        $store->store_description = $newStore->store_description;
        $store->store_pgp       = $newStore->user->pgp_key;
        $store->store_key   = $newStore->user->store_key;
        $store->selling     = $newStore->selling;
        $store->ship_from   =  $newStore->ship_from;
        $store->ship_to     = $newStore->ship_to;
        $store->last_updated = now();
        $store->avatar      = $newStore->avater;
        $store->save();
        return true;
    }


    public function store($created_at, Store $store)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($store->created_at) && $auth_user->role == 'senior') {

            return view('Senior.index', [
                'user' => $auth_user,
                'store' => $store,
                'action'  => 'Store',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }



    public function newFAQ(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        if ($request->has('new_faq')) {
            return redirect()->back()->with('new_faq', true);
        }

        if (($request->has('edit') || $request->has('delete')) && $request->has('faq')) {
            $request->validate([
                'faq' => 'required|string|min:32|max:256',
            ]);

            $id = Crypt::decrypt($request->faq);
            $faq = FAQ::find($id);

            if ($request->has('edit') && ($faq->user_id == auth()->user()->id || auth()->user()->role == 'admin')) {
                return redirect()->back()->with('faq', $faq);
            }

            if ($request->has('delete') && ($faq->user_id == auth()->user()->id || auth()->user()->role == 'admin')) {
                $faq->delete();
                return redirect()->back()->with('success', "FAQ has been successfully deleted.");
            }

            return redirect()->back();
        }

        if ($request->has('equestion') && $request->has('eanswer')) {
            $request->validate([
                'faq' => 'required|string|min:32|max:256',
                'equestion' => 'required|string',
                'eanswer'  => 'required|string',
            ]);

            $id = Crypt::decrypt($request->faq);
            $faq = FAQ::find($id);
            $faq->question = $request->equestion;
            $faq->answer  = $request->eanswer;


            if (($faq->user_id == auth()->user()->id || auth()->user()->role == 'admin')) {
                $faq->save();
                return redirect()->back()->with('success', "FAQ has been successfully uppdated.");
            }
            return redirect()->back();
        }

        if ($request->has('question') && $request->has('answer')) {
            $request->validate([
                'question' => 'required|string|min:5|max:500',
                'answer'   => 'required|string|min:10|max:2000',
            ]);

            $faq = new FAQ();
            $faq->user_id = auth()->user()->id;
            $faq->question = $request->question;
            $faq->answer   = $request->answer;
            $faq->save();


            return redirect()->back()->with("success", "You have successfully added an FAQ");
        }
    }


    public function updateCanary(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $user = auth()->user();

        if ($request->has('update_canary')) {
            return redirect()->back()->with('update_canary', true);
        }

        if ($request->has('canary_message')) {
            $request->validate([
                'canary_message' => 'required|string|min:50|max:5000',
            ]);

            if ($user->canary === null) {
                $canary = new MarketKey();
                $canary->user_id = $user->id;
                $canary->message_sign = $request->canary_message;
                $canary->public_key   = '';
                $canary->save();
            } else {
                $canary = $user->canary;
                $canary->message_sign = $request->canary_message;
                $canary->public_key   = '';
                $canary->save();
            }

            return redirect()->back()->with("success", "You have successfully added an FAQ");
        }
    }


    public function addNews(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $user = auth()->user();

        if ($request->has('new_news')) {
            return redirect()->back()->with('new_news', true);
        }

        if ($request->has('edit')) {
            $request->validate([
                'news' => 'required|string|min:32|max:256',
            ]);
            $id = Crypt::decrypt($request->news);
            $news = News::find($id);
            return redirect()->back()->with('news', $news);
        }

        if ($request->has('title')) {
            $request->validate([
                'title' => 'required|string|min:10|max:255',
                'contents'   => 'required|string|min:10|max:5000',
            ]);
            $news = new News();
            $news->author_id = $user->id;
            $news->title = $request->title;
            $news->content  = $request->contents;
            $news->save();
            return redirect()->back()->with("success", "You have successfully added a news, it not deletable.");
        } elseif ($request->has('news') && $request->has('etitle')) {
            $request->validate([
                'news' => 'required|string|min:32|max:256',
                'etitle' => 'required|string|min:10|max:255',
                'contents'   => 'required|string|min:10|max:5000',
            ]);

            $id = Crypt::decrypt($request->news);
            $news = News::find($id);
            $news->title = $request->etitle;
            $news->content  = $request->contents;
            $news->save();
            return redirect()->back()->with("success", "You have successfully updated a news, it not deletable.");
        }


        return redirect()->back();
    }

    public function showWaiver($user, $created_at, Waiver $waiver)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($waiver->created_at) && $auth_user->role == 'senior') {

            return view('Senior.index', [
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

            $notificationType = NotificationType::where('action', 'waiver_approved')->where('icon', 'store')->first();

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


    public function showStoreReviews($created_at, Store $store)
    {
        if ($created_at == strtotime($store->created_at)) {
            return view(
                'Senior.storeReviews',
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
                'Senior.productReviews',
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

    public function banUnban($created_at, Store $store, Request $request)
    {
        
        if ($created_at == strtotime($store->created_at)) {
            if ($request->has('ban')) {
                $store->status = 'banned';
                $store->save();

                $store->user->status = 'banned';
                $store->user->store_status = 'banned';
                $store->user->save();

                return redirect()->back();
            } else if ($request->has('unban')) {
                $store->status = 'active';
                $store->save();

                $store->user->status = 'active';
                $store->user->store_status = 'active';
                $store->user->save();

                return redirect()->back();
            } elseif ($request->has('verify')) {
                $store->is_verified = true;
                $store->save();
                return redirect()->back();
            } elseif ($request->has('enable_fe')) {
                $store->is_fe_enable = true;
                $store->save();
                return redirect()->back();
            } elseif ($request->has('un_verify')) {
                $store->is_verified = false;
                $store->save();
                return redirect()->back();
            } elseif ($request->has('disable_fe')) {
                $store->is_fe_enable = false;
                $store->save();
                return redirect()->back();
            }
        }
    }

    public function approveReject($created_at, Product $product, Request $request)
    {
        if ($created_at == strtotime($product->created_at)) {
            if ($request->has('reject')) {
                $product->status = 'Rejected';
                $product->save();
                $nextProduct = Product::where('status', 'Pending')->first();
                if (!empty($nextProduct)) {
                    if (auth()->user()->role == 'admin') {
                        return redirect('/whales/admin/show/product/' . strtotime($nextProduct->created_at) . '/' . $nextProduct->id);
                    } else {
                        return redirect('/senior/staff/show/product/' . strtotime($nextProduct->created_at) . '/' . $nextProduct->id);
                    }
                } else {
                    return redirect()->back()->with('success', 'There are nor more pending products Good job for now!');
                }
                return redirect()->back();
            } elseif ($request->has('approve')) {
                $product->status = 'Active';
                $product->save();
                return redirect()->back();
               $nextProduct = Product::where('status', 'Pending')->first();
                if (!empty($nextProduct)) {
                    if (auth()->user()->role == 'admin') {
                        return redirect('/whales/admin/show/product/' . strtotime($nextProduct->created_at) . '/' . $nextProduct->id);
                    } else {
                        return redirect('/senior/staff/show/product/' . strtotime($nextProduct->created_at) . '/' . $nextProduct->id);
                    }
                } else {
                    return redirect()->back()->with('success', 'There are nor more pending products Good job for now!');
                }
            }
        }
    }


    public function unauthorizeAccess($created_at, Unauthorize $unauthorize)
    {

        if (auth()->user()->role == 'admin') {
                                        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
                                        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                                            return redirect('/auth/whales/admin/pgp/verify');
                                        }
        }else{
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        }
        $auth_user = auth()->user();

        if ($created_at == strtotime($unauthorize->created_at) && $auth_user->role == 'senior') {

            return view('Senior.index', [
                'user' => $auth_user,
                'unauthorize' => $unauthorize,
                'show_user'  => $unauthorize->user,
                'action'  => 'unauthorize',
                'icon'  => GeneralController::encodeImages(),
                'product_image' => GeneralController::encodeImages('Product_Images'),
                'upload_image' => GeneralController::encodeImages('Upload_Images'),
            ]);
        }
    }

    public function modMail($user)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        $auth_user = auth()->user();
        return view('Senior.index', [
            'user' => $auth_user,
            'staffs' => User::where('role', 'admin')->orwhere('role', 'senior')->orwhere('role', 'junior')->get(),
            'action'  => 'modmail',
            'icon'  => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
        ]);
    }


    public function settings(Request $request)
    {


        if (auth()->user()->role == 'admin') {
                            //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
                            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                                return redirect('/auth/whales/admin/pgp/verify');
                            }
        }else{
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }
        }
        // Validate the request data
        $request->validate([
            'status'                => 'required|string|in:vacation,active',
            'avatar'                => 'sometimes|image|mimes:jpeg,png,jpg|max:2000',
            'security_code'           => 'required|string|min:6|max:10',
        ]);

        if (auth()->user()->pin_code != $request->security_code) {
            return redirect()->back()->with('error', 'Wrong secret code!');
        }

        $user = auth()->user();

        $user->status            = $request->status;

        // Check if the submitted avatar is different from the current one Update the avatar only if it's different
        if ($request->avater != null && $request->avater != $user->avater) {
            $user->avater = GeneralController::processAndStoreImage($request->avater, 'Upload_Images');
        }

        $user->save();

        return redirect()->back()->with('success', 'You have successfully updated your settings.');
    }
}
