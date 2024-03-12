<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Dispute;
use App\Models\Escrow;
use App\Models\Message;
use App\Models\NewStore;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Product;
use App\Models\Promocode;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SearchController extends Controller
{

    public function quickListtingSearch($name, $created_at, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $is_parent_category = false;
        $is_sub_category = false;
        $categoryName = null;

        $validatedData = $request->validate([
            'search_listings' => 'required|string',
        ]);

        $productName = $validatedData['search_listings'];

        $query = Product::query()->where('status', 'Active');

        // Assuming the User model has a relationship named 'blockedStores'
        $userBlockedStoreIds = auth()->user()->blockedStores()->pluck('store_id')->toArray();

        $products = $query->inRandomOrder()
            ->whereHas('store', function ($query) use ($userBlockedStoreIds) {
                $query->where('status', 'Active')
                    ->whereNotIn('id', $userBlockedStoreIds); // Assuming 'id' is the primary key of the stores table
            });

        $products = $this->SearchListingsQuery($query, $productName);


        // Execute the query and get the search results
        $results = $products->get();

        // For example, you can return a view with the search results
        return view('User.search', [
            'search_products' => $results,
            'user'  => auth()->user(),
            'icon' => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
            'action' => 'users',
            'name' => 'Users',
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'is_parent_category' => $is_parent_category,
            'is_sub_category' => $is_sub_category,
            'categoryName' => $categoryName,
        ]);
    }

    private function SearchListingsQuery($query, $productName)
    {
        if (!empty($productName)) {
            $query->where('product_name', 'like', '%' . $productName . '%');
        }

        $query->orderBy('sold', 'desc');

        // Default sorting to random
        $query->inRandomOrder();

        // Execute the query and return the result set
        return $query;
    }



    public function quickSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        $is_parent_category = false;
        $is_sub_category = false;
        $categoryName = null;

        $validatedData = $request->validate([
            'pn' => 'nullable|string',
            'str_n' => 'nullable|string',
            'pf' => 'nullable|numeric|min:0',
            'pt' => 'nullable|numeric|min:0',
            'sf' => 'nullable|string',
            'st' => 'nullable|string',
            'auto_shop' => 'nullable',
            'desc'  => 'nullable',
            'payment_type' => 'nullable|in:Escrow,FE',
            'filter-product' => 'nullable|in:best-match,newest,oldest,Cheapest,highest',
            'parent_category' => 'nullable|exists:categories,id',
            'sub_category' => 'nullable|exists:categories,id',
            'pt2' => 'nullable|in:all,digital,physical',
        ]);

        $storeName = $validatedData['str_n'] ?? '';
        $productName = $validatedData['pn'];
        $minPrice = $validatedData['pf'];
        $maxPrice = $validatedData['pt'];
        $sortBy = $validatedData['filter-product'];
        $parent_categoryId = $validatedData['parent_category'] ?? '';
        $sub_categoryId = $validatedData['sub_category'] ?? '';
        $ship_from  = $validatedData['sf'] ?? '';
        $ship_to = $validatedData['st'] ?? '';
        $auto_shop = $validatedData['auto_shop'] ?? '';
        $desc = $validatedData['desc'] ?? '';
        $payment_type = $validatedData['payment_type'] ?? '';
        $productType = $validatedData['pt2'] ?? '';

        $query = Product::query()->where('status', 'Active');

        // Assuming the User model has a relationship named 'blockedStores'
        $userBlockedStoreIds = auth()->user()->blockedStores()->pluck('store_id')->toArray();

        $products = $query
            ->whereHas('store', function ($query) use ($userBlockedStoreIds) {
                $query->where('status', 'Active')
                    ->whereNotIn('id', $userBlockedStoreIds); // Assuming 'id' is the primary key of the stores table
            });

        $products = $this->productSearchQuery($query, $productName, $minPrice, $maxPrice, $sortBy, $parent_categoryId, $sub_categoryId, $productType, $ship_from, $ship_to, $auto_shop, $desc, $payment_type);


        // Execute the query and get the search results
        $results = $products->get();

        // For example, you can return a view with the search results
        return view('User.search', [
            'search_products' => $results,
            'user'  => auth()->user(),
            'icon' => GeneralController::encodeImages(),
            'product_image' => GeneralController::encodeImages('Product_Images'),
            'upload_image' => GeneralController::encodeImages('Upload_Images'),
            'action' => 'users',
            'name' => 'Users',
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'is_parent_category' => $is_parent_category,
            'is_sub_category' => $is_sub_category,
            'categoryName' => $categoryName,
        ]);
    }


    private function productSearchQuery($query, $productName, $minPrice, $maxPrice, $sortBy, $parent_categoryId, $sub_categoryId, $productType, $ship_from, $ship_to, $auto_shop, $desc, $payment_type)
    {
        if (!empty($productName)) {
            $query->where('product_name', 'like', '%' . $productName . '%');
        }

        if (!empty($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }

        if (!empty($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }


        if (!empty($sortBy)) {
            // Sorting logic based on $sortBy
            switch ($sortBy) {
                case 'best-match':
                    $query->orderBy('sold', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'Cheapest':
                    $query->orderBy('price', 'asc');
                    break;
                case 'highest':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    // Default sorting to random
                    $query->inRandomOrder();
            }
        }

        if (!empty($ship_from)) {
            $query->where('ship_from', $ship_from);
        }

        if (!empty($ship_to)) {
            $query->where('ship_to', $ship_to);
        }

        if (!empty($auto_shop)) {
            $query->where('auto_delivery_content', '!=', NULL);
        }

        if (!empty($desc)) {
            $query->orWhere('product_description', 'like', '%' . $productName . '%');
        }




        if (!empty($payment_type)) {
            $query->where('payment_type', $payment_type);
        }

        if (!empty($parent_categoryId)) {
            $query->where('parent_category_id', $parent_categoryId);
        }

        if (!empty($sub_categoryId)) {
            $query->where('sub_category_id', $sub_categoryId);
        }

        if (!empty($productType) && $productType != 'all') {
            $query->where('product_type', $productType);
        }

        if (!empty($storeName)) {
            $query->whereHas('store', function ($storeQuery) use ($storeName) {
                $storeQuery->where('store_name', $storeName);
            });
        }

        // Execute the query and return the result set
        return $query;
    }



    // store searching products
    public function storeProductsSearch($actionName, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }
        // Validate the incoming request data
        $store = auth()->user()->store;
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'popular', 'price_highest', 'price_lowest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'payment_type' => ['nullable', 'string', Rule::in(['all', 'Escrow', 'FE'])],
            'status' => ['nullable', Rule::in(['all', 'Active', 'Pending', 'Rejected', 'Paused'])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $search_term = $request->search_term;
        $payment_type = $request->payment_type;

        // Build the query based on the search parameters
        $query = Product::query()->where('store_id', $store->id);

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc'); // Change to 'desc' for newest
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc'); // Change to 'asc' for oldest
                    break;

                case 'popular':
                    $query->orderBy('sold', 'desc'); // Assuming 'popular' means highest sold
                    break;

                case 'price_lowest':
                case 'price_highest':
                    $orderDirection = ($sort_by == 'price_lowest') ? 'asc' : 'desc';
                    $query->orderBy('price', $orderDirection);
                    break;

                default:
                    break;
            }
        }

        if ($payment_type !== 'all') {
            $query->where('payment_type', $payment_type);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($search_term)) {
            $query->where('product_name', 'like', '%' . $search_term . '%');
        }

        // Execute the query to retrieve products
        $products = $query->paginate($number_of_rows);

        // Return the products to the view
        return redirect()->back()->with('products', $products);
    }


    public function storeOrdersSearch($actionName, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        // Validate the incoming request data
        $store = auth()->user()->store;
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'highest_quantity', 'lowest_quantity', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'payment_type' => ['nullable', 'string', Rule::in(['all', 'Escrow', 'FE'])],
            'status' => ['nullable', Rule::in(['all', 'pending', 'processing', 'shipped', 'delivered', 'dispute', 'sent', 'dispatched', 'cancelled', 'completed'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $payment_type = $request->payment_type;

        // Build the query based on the search parameters
        $query = Order::query()->where('store_id', $store->id);

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'highest_quantity':
                    $query->orderBy('quantity', 'desc');
                    break;

                case 'lowest_quantity':
                    $query->orderBy('quantity', 'asc');
                    break;

                default:
                    break;
            }
        }

        if ($payment_type !== 'all') {
            $query->whereHas('product', function ($productQuery) use ($payment_type) {
                $productQuery->where('payment_type', $payment_type);
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Execute the query to retrieve orders
        $orders = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('orders', $orders);
    }


    public function adminOrdersSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        // Validate the incoming request data
        $store = auth()->user()->store;
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'highest_quantity', 'lowest_quantity', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'payment_type' => ['nullable', 'string', Rule::in(['all', 'Escrow', 'FE'])],
            'status' => ['nullable', Rule::in(['all', 'pending', 'processing', 'shipped', 'delivered', 'dispute', 'sent', 'dispatched', 'cancelled', 'completed'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $payment_type = $request->payment_type;

        // Build the query based on the search parameters
        $query = Order::query();

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'highest_quantity':
                    $query->orderBy('quantity', 'desc');
                    break;

                case 'lowest_quantity':
                    $query->orderBy('quantity', 'asc');
                    break;

                default:
                    break;
            }
        }

        if ($payment_type !== 'all') {
            $query->whereHas('product', function ($productQuery) use ($payment_type) {
                $productQuery->where('payment_type', $payment_type);
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Execute the query to retrieve orders
        $orders = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('orders', $orders);
    }

    public function userOrderSearch($user, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'highest_quantity', 'lowest_quantity', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'payment_type' => ['nullable', 'string', Rule::in(['all', 'Escrow', 'FE'])],
            'status' => ['nullable', Rule::in(['all', 'pending', 'processing', 'shipped', 'delivered', 'dispute', 'sent', 'dispatched', 'cancelled', 'completed'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $payment_type = $request->payment_type;

        // Build the query based on the search parameters
        $query = Order::query()->where('user_id', $user->id);

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'highest_quantity':
                    $query->orderBy('quantity', 'desc');
                    break;

                case 'lowest_quantity':
                    $query->orderBy('quantity', 'asc');
                    break;

                default:
                    break;
            }
        }

        if ($payment_type !== 'all') {
            $query->whereHas('product', function ($productQuery) use ($payment_type) {
                $productQuery->where('payment_type', $payment_type);
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Execute the query to retrieve orders
        $orders = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('orders', $orders);
    }


    public function userMessageSearch($user, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->role == 'senior') {
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/senior/pgp/verify');
            }
        } else if (auth()->user()->role == 'user') {
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/pgp/verify');
            }
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'highest_quantity', 'lowest_quantity', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'message_type' => ['nullable', 'string', Rule::in(['all', 'message', 'ticket', 'dispute', 'mass', 'staff'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $message_type = $request->message_type;

        // Build the query based on the search parameters
        $query = Conversation::query();
        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->with(['messages' => function ($subquery) use ($message_type, $user) {
                        $subquery->where('user_id', $user->id)
                            ->where('message_type', $message_type)
                            ->orderByDesc('created_at')
                            ->limit(1); // Limit to the latest message for each conversation
                    }])
                        ->orderByDesc('created_at'); // Order by the latest message's timestamp
                    break;

                case 'oldest':
                    $query->with(['messages' => function ($subquery) use ($message_type, $user) {
                        $subquery->where('user_id', $user->id)
                            ->where('message_type', $message_type)
                            ->orderBy('created_at')
                            ->limit(1); // Limit to the oldest message for each conversation
                    }])
                        ->orderBy('created_at');
                    break;

                default:
                    break;
            }
        }

        if ($message_type != 'all') {
            $query->whereHas('messages', function ($subquery) use ($user, $message_type) {
                $subquery->where('user_id', $user->id)->where('message_type', $message_type);
            });
        }

        // Execute the query to retrieve conversations
        $conversations = $query->paginate($number_of_rows);
        $participants = Participant::where('user_id', auth()->user()->id)->get();


        // Return the orders to the view
        return redirect()->back()->with('conversations', $conversations)->with('participants', $participants);
    }

    public function userNotificationsSearch($user, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->role == 'senior') {
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/senior/pgp/verify');
            }
        } else if (auth()->user()->role == 'user') {
            if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                return redirect('/auth/pgp/verify');
            }
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            // 'type' => ['nullable', 'string', Rule::in(['all', 'orders', 'wallet', 'account', 'news', 'share', 'referral', 'listings', 'reports', 'bugs'])],
            'status' => ['nullable', Rule::in(['all', 'read', 'unread'])],
            'action' => ['nullable', Rule::in(['show', 'read_all', 'delete'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $action  = $request->action;

        // Build the query based on the search parameters
        $query = Notification::query()->where('user_id', $user->id);

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    break;
            }
        }


        if ($status == 'read') {
            $query->where('is_read', 1);
        }

        if ($status == 'unread') {
            $query->where('is_read', 0);
        }

        // Handle the specified action
        switch ($action) {
            case 'show':
                break;

            case 'read_all':
                $query->update(['is_read' => 1]);
                break;

            case 'delete':
                $query->delete();
                break;

            default:
                break;
        }

        // Execute the query to retrieve orders
        $notifications = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('notifications',  $notifications);
    }

    public function storeNotificationsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        // Validate the incoming request data
        $store = auth()->user()->store;
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            // 'type' => ['nullable', 'string', Rule::in(['all', 'orders', 'wallet', 'account', 'news', 'share', 'referral', 'listings', 'reports', 'bugs'])],
            'status' => ['nullable', Rule::in(['all', 'read', 'unread'])],
            'action' => ['nullable', Rule::in(['show', 'read_all', 'delete', 'clear'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $action  = $request->action;

        // Build the query based on the search parameters
        $query = Notification::query()->where('user_id', $store->user_id);

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    break;
            }
        }

        if ($status == 'read') {
            $query->where('is_read', 1);
        }

        if ($status == 'unread') {
            $query->where('is_read', 0);
        }

        // Handle the specified action
        switch ($action) {
            case 'show':
                break;

            case 'read_all':
                $query->update(['is_read' => 1]);
                break;

            case 'delete':
                $query->delete();
                break;

            default:
                break;
        }

        // Execute the query to retrieve orders
        $orders = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('notifications', $orders);
    }


    // staff routes
    public function staffSearchUsers($user, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'role' => ['nullable', 'string', Rule::in(['all', 'user', 'store', 'share', 'junior', 'senior', 'admin'])],
            'status' => ['nullable', 'string', Rule::in(['all', 'active', 'banned'])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $search_term = $request->search_term;
        $role        = $request->role;


        $query = User::query();

        if (auth()->user()->role == 'admin' && !empty($role) && $role != 'all') {
            $query->where('role', $role);
        } else {
            $query->where('role', 'user');
        }

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }

        if (!empty($search_term)) {
            $query->where('public_name', 'like', '%' . $search_term . '%');
        }

        // Execute the query to retrieve orders
        $users = $query->paginate($number_of_rows);
        // Return the orders to the view
        return redirect()->back()->with('users', $users);
    }

    public function staffSearchNewStores($user, Request $request)
    {
        // Check if the user has 2fa enabled and if they have verified it, else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status' => ['nullable', 'string', Rule::in(['all', 'active', 'in_active', 'pending'])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $search_term = $request->search_term;

        $query = NewStore::query();

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }


        if (!empty($status) && $status != 'all') {
            $query->whereHas('user', function ($userQuery) use ($status) {
                $userQuery->where('store_status',  $status);
            });
        }

        if (!empty($search_term)) {
            $query->whereHas('user', function ($userQuery) use ($search_term) {
                $userQuery->where('public_name', 'like', '%' . $search_term . '%');
            });
        }

        // Execute the query to retrieve new stores
        $newStores = $query->paginate($number_of_rows);

        // Return the new stores to the view
        return redirect()->back()->with('newStores', $newStores);
    }

    public function staffSearchStores($user, Request $request)
    {
        // Check if the user has 2fa enabled and if they have verified it, else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status' => ['nullable', 'string', Rule::in(['all', 'active', 'vacation', 'banned'])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $search_term = $request->search_term;

        $query = Store::query();

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }

        if (!empty($search_term)) {
            $query->where('store_name', 'like', '%' . $search_term . '%');
        }

        // Execute the query to retrieve stores
        $stores = $query->paginate($number_of_rows);

        // Return the stores to the view
        return redirect()->back()->with('stores', $stores);
    }

    public function staffSearchProducts($user, Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'popular', 'price_highest', 'price_lowest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'payment_type' => ['nullable', 'string', Rule::in(['all', 'Escrow', 'FE'])],
            'status' => ['nullable', Rule::in(['all', 'Active', 'Pending', 'Rejected', 'Paused'])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $search_term = $request->search_term;
        $payment_type = $request->payment_type;

        // Build the query based on the search parameters
        $query = Product::query();

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc'); // Change to 'desc' for newest
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc'); // Change to 'asc' for oldest
                    break;

                case 'popular':
                    $query->orderBy('sold', 'desc'); // Assuming 'popular' means highest sold
                    break;

                case 'price_lowest':
                case 'price_highest':
                    $orderDirection = ($sort_by == 'price_lowest') ? 'asc' : 'desc';
                    $query->orderBy('price', $orderDirection);
                    break;

                default:
                    break;
            }
        }

        if ($payment_type !== 'all') {
            $query->where('payment_type', $payment_type);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($search_term)) {
            $query->where('product_name', 'like', '%' . $search_term . '%');
        }

        // Execute the query to retrieve products
        $products = $query->paginate($number_of_rows);

        // Return the products to the view
        return redirect()->back()->with('products', $products);
    }

    public function staffSearchDisputes($user, Request $request)
    {
        // Check if the user has 2fa enabled and if they have verified it, else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'amount_highest', 'amount_lowest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status' => ['nullable', 'string', Rule::in(['all', 'open', 'Full Refund', 'Partial Refund', 'closed'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;

        $query = Dispute::query();

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'amount_highest':
                    $query->with(['escrow' => function ($escrowQuery) {
                        $escrowQuery->orderBy('fiat_amount', 'desc');
                    }]);
                    break;

                case 'amount_lowest':
                    $query->with(['escrow' => function ($escrowQuery) {
                        $escrowQuery->orderBy('fiat_amount', 'asc');
                    }]);
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }

        // Execute the query to retrieve disputes
        $disputes = $query->paginate($number_of_rows);

        // Return the disputes to the view
        return redirect()->back()->with('disputes', $disputes);
    }

    public function staffSearchSupports($user, Request $request)
    {
        // Check if the user has 2fa enabled and if they have verified it, else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status' => ['nullable', 'string', Rule::in(['all', 'open', 'pending', 'closed'])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $search_term = $request->search_term;

        $query = Support::query();

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }

        if (!empty($search_term)) {
            $query->whereHas('user', function ($userQuery) use ($search_term) {
                $userQuery->where('public_name', 'like', '%' . $search_term . '%');
            });
        }


        // Execute the query to retrieve supports
        $supports = $query->paginate($number_of_rows);

        // Return the supports to the view
        return redirect()->back()->with('supports', $supports);
    }

    public function staffSearchReports($user, Request $request)
    {
        // Check if the user has 2fa enabled and if they have verified it, else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'highest_reported', 'highest_reporter', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status' => ['nullable', 'string', Rule::in(['all', 'pending', 'verified', 'fake'])],
            'type' => ['nullable', 'string', Rule::in(['all', 'store', 'listing'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $type = $request->type;

        $query = Report::query();

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                    // case 'highest_reported':
                    //     // Logic to order by the highest number of reports
                    //     $query->orderBy('reported_count', 'desc');
                    //     break;

                    // case 'highest_reporter':
                    //     // Logic to order by the user with the highest number of reports
                    //     $query->orderBy('', 'desc');
                    //     break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }

        if (!empty($type) && $type = 'store') {
            $query->where('is_store', 1);
        }

        if (!empty($type) && $type = 'listing') {
            $query->where('is_store', 0);
        }

        // Execute the query to retrieve reports
        $reports = $query->paginate($number_of_rows);

        // Return the reports to the view
        return redirect()->back()->with('reports', $reports);
    }

    public function staffSearchWaivers($user, Request $request)
    {
        // Check if the user has 2fa enabled and if they have verified it, else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status' => ['nullable', 'string', Rule::in(['all', 'pending', 'approved', 'rejected'])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status = $request->status;
        $search_term = $request->search_term;

        $query = Waiver::query();

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }

        if (!empty($search_term)) {
            $query->whereHas('user', function ($userQuery) use ($search_term) {
                $userQuery->where('public_name', 'like', '%' . $search_term . '%');
            });
        }

        // Execute the query to retrieve waivers
        $waivers = $query->paginate($number_of_rows);

        // Return the waivers to the view
        return redirect()->back()->with('waivers', $waivers);
    }

    public function staffSearchUnauthorizes($user, Request $request)
    {
        // Check if the user has 2fa enabled and if they have verified it, else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/staff/senior/pgp/verify');
        }

        // Validate the incoming request data
        $user = auth()->user();
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'search_term' => ['nullable', 'string'],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $search_term = $request->search_term;

        $query = Unauthorize::query();

        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($search_term)) {
            $query->whereHas('user', function ($userQuery) use ($search_term) {
                $userQuery->where('public_name', 'like', '%' . $search_term . '%');
            });
        }

        // Execute the query to retrieve unauthorized accesses
        $unauthorizes = $query->paginate($number_of_rows);

        // Return the unauthorized accesses to the view
        return redirect()->back()->with('unauthorizes', $unauthorizes);
    }


    public function adminCartsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        // Validate the incoming request data
        $store = auth()->user()->store;
        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'highest_quantity', 'lowest_quantity', 'product_highest', 'price_highest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;

        // Build the query based on the search parameters
        $query = Cart::query();

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'highest_quantity':
                    $query->orderBy('quantity', 'desc');
                    break;

                case 'product_highest':
                    $query->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                        ->groupBy('product_id')
                        ->orderBy('total_quantity', 'desc');
                    break;

                case 'price_highest':
                    $query->orderBy('quantity', 'desc');
                    break;

                case 'lowest_quantity':
                    $query->orderBy('quantity', 'asc');
                    break;

                default:
                    break;
            }
        }

        // Execute the query to retrieve orders
        $carts = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('carts', $carts);
    }

    public function adminConversationsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'archive', 'highest_messages', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;

        // Build the query based on the search parameters
        $query = Conversation::query();

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'archive':
                    $query->whereHas('participants', function ($participantQuery) {
                        $participantQuery->where('is_hidden', 1);
                    })->orderBy('created_at', 'desc');
                    break;

                case 'highest_messages':
                    $query->withCount('messages')->orderBy('messages_count', 'desc');
                    break;

                default:
                    break;
            }
        }

        // Execute the query to retrieve orders
        $conversations = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('conversations', $conversations);
    }

    public function adminEscrowsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'amount_highest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status'  => ['nullable', Rule::in(['all', 'active', 'released', 'cancelled'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status    = $request->status;

        // Build the query based on the search parameters
        $query = Escrow::query();

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'amount_highest':
                    $query->orderBy('fiat_amount', 'desc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }
        // Execute the query to retrieve orders
        $escrows = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('escrows', $escrows);
    }

    public function adminCouponsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'usage_highest', 'used_highest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'status'  => ['nullable', Rule::in(['all', 'active', 'expired'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $status    = $request->status;

        // Build the query based on the search parameters
        $query = Promocode::query();

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'usage_highest':
                    $query->orderBy('usage_limit', 'desc');
                    break;

                case 'used_highest':
                    $query->orderBy('times_used', 'desc');
                    break;

                default:
                    break;
            }
        }

        if (!empty($status) && $status != 'all') {
            $query->where('status', $status);
        }
        // Execute the query to retrieve orders
        $promos = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('promos', $promos);
    }

    public function adminReviewsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'usage_highest', 'used_highest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'store'     => ['nullable', 'string'],
            'type'  => ['nullable', Rule::in(['all', 'positive', 'neutral', 'negative'])],
        ]);

        if (auth()->user()->role == 'store' || auth()->user()->role == 'user') {
            $store = Crypt::decrypt($request->store);
        } else {
            $store   = $request->store;
        }

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $type    = $request->type;


        // Build the query based on the search parameters
        $query = Review::query();

        if (!empty($type) && $type != 'all') {
            $query->where('feedback', $type);
        }

        if (!empty($store) && $store != '') {
            $query->where('store_id', $store);
        }

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }



        // Execute the query to retrieve orders
        $reviews = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('reviews', $reviews);
    }



    public function productReviewsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'usage_highest', 'used_highest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'product'     => ['nullable', 'string'],
            'type'  => ['nullable', Rule::in(['all', 'positive', 'neutral', 'negative'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $type    = $request->type;

        if (auth()->user()->role == 'store' || auth()->user()->role == 'user') {
            $product   = Crypt::decrypt($request->product);
        } else {
            $product   = $request->product;
        }
        // Build the query based on the search parameters
        $query = Review::query();

        if (!empty($type) && $type != 'all') {
            $query->where('feedback', $type);
        }

        if (!empty($product) && $product != '') {
            $query->where('product_id', $product);
        }

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }



        // Execute the query to retrieve orders
        $reviews = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('reviews', $reviews);
    }


    public function adminWalletsSearch(Request $request)
    {
        //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
        if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
            return redirect('/auth/store/pgp/verify');
        }

        $request->validate([
            'sort_by' => ['nullable', Rule::in(['newest', 'balance_highest', 'oldest'])],
            'number_of_rows' => ['nullable', 'integer', Rule::in([50, 100, 150, 250])],
            'role'     => ['nullable', Rule::in(['all', 'user', 'store', 'junior', 'senior', 'admin'])],
            'status'  => ['nullable', Rule::in(['all', 'active', 'vaction', 'banned'])],
        ]);

        // Retrieve validated search parameters from the request
        $sort_by = $request->sort_by;
        $number_of_rows = $request->number_of_rows;
        $role    = $request->role;
        $status   = $request->status;

        // Build the query based on the search parameters
        $query = Wallet::query();

        if (!empty($search_term)) {
            $query->whereHas('user', function ($userQuery) use ($search_term) {
                $userQuery->where('public_name', 'like', '%' . $search_term . '%');
            });
        }

        if (!empty($role) && $role != 'all') {
            $query->whereHas('user', function ($userQuery) use ($role) {
                $userQuery->where('role', $role);
            });
        }

        if (!empty($status) && $status != 'all') {
            $query->whereHas('user', function ($userQuery) use ($status) {
                $userQuery->where('status', $status);
            });
        }

        // Assuming $query is an instance of Eloquent query builder
        if (!empty($sort_by)) {
            switch ($sort_by) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;

                case 'balance_highest':
                    $query->orderBy('balance', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                default:
                    break;
            }
        }



        // Execute the query to retrieve orders
        $wallets = $query->paginate($number_of_rows);

        // Return the orders to the view
        return redirect()->back()->with('wallets', $wallets);
    }
}
