<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlockStoreController;
use App\Http\Controllers\BugController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\FavoriteListingController;
use App\Http\Controllers\FavoriteStoreController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewStoreController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;


Route::middleware(['role:user'])->group(function () {

    Route::get('/report/store/{name}/{created_at}/{store}', [ReportController::class, 'reportStore']);
    Route::get('/report/listing/{name}/{created_at}/{product}', [ReportController::class, 'reportListing']);

    Route::post('/report/store/{name}/{created_at}/{store}', [ReportController::class, 'storeUser']);
    Route::post('/report/listing/{name}/{created_at}/{product}', [ReportController::class, 'listing']);

    Route::post('/{user}/{created_at}/change_currency', [UserController::class, 'currency']);

    Route::post('/{user}/{created_at}/{store}/listing/{porduct_created_at}/{product}', [UserController::class, 'likeCartPlus']);

    Route::get('/kick/{user}/out', [UserController::class, 'kickout']);

    Route::post('/', [UserController::class, 'show']);

    Route::get('/user/captcha', [GeneralController::class, 'captcha']);

    // withdraws
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    // search quick and advance
    Route::get('/search', [SearchController::class, 'quickSearch']);
    Route::get('/parent/category/{created_at}/{category}', [CategoryController::class, 'parentCategoryProducts']);
    Route::get('/sub/category/{created_at}/{category}', [CategoryController::class, 'subCategoryProducts']);

    // archive conversation
    Route::post('/conversation/archive/{created_at}/{conversation}', [ConversationController::class, 'archiveConv']);

    // Get the qrcode
    Route::get('/account/deposit/qrcode', [GeneralController::class, 'qrcode']);
    Route::post('/account/changePassword', [UserController::class, 'changePassword']);
    Route::post('/bugs', [BugController::class, 'store']);
    Route::post('/account/storeKey', [UserController::class, 'storeKey']);

    // Delete methods
    Route::delete('/blocked/b_store/{blockStore}', [BlockStoreController::class, 'destroy']);
    Route::delete('/favorite/f_store/{favoriteStore}', [FavoriteStoreController::class, 'destroy']);
    Route::delete('/favorite/f_listing/{favoriteListing}', [FavoriteListingController::class, 'destroy']);

    Route::post('/open-store', [UserController::class, 'openstore']);
    Route::get('/store/waiver', [NewStoreController::class, 'waiver']);
    Route::post('/store/waiver', [NewStoreController::class, 'waiverAdd']);


    // Store routes
    Route::get('/store/show/reviews/{store}/search', [SearchController::class, 'adminReviewsSearch']);
    Route::get('/store/show/{name}/{created_at}/{store}', [StoreController::class, 'show']);
    Route::get('/store/show/pgp/{name}/{created_at}/{store}', [StoreController::class, 'pgp']);
    Route::get('/store/show/reviews/{name}/{created_at}/{store}', [StoreController::class, 'reviews']);
    Route::post('/report/store/{name}/created_at}/{store}', [ReportController::class, 'storeUser']);
    Route::post('/store/show/{name}/{created_at}/{store}', [StoreController::class, 'checkAction']);
    Route::get('/open-store', [NewStoreController::class, 'create']);

    // Listings  
    Route::get('/listing/{created_at}/{product}', [ProductController::class, 'show']);
    Route::post('/listing/{created_at}/{product}', [ProductController::class, 'checkAction']);
    Route::post('/listing/report/{created_at}/{product}', [ReportController::class, 'listing']);
    Route::get('/listing/reviews/{created_at}/{product}', [ProductController::class, 'reviews']);

    // Messages for store
    Route::get('/store/show/message/{name}/{created_at}/{store}', [MessageController::class, 'create']);
    Route::post('/store/show/message/{name}/{created_at}/{store}', [ConversationController::class, 'store']);

    Route::post('/messages/{created_at}/{conversation}', [MessageController::class, 'store']);
    Route::get('/messages/{created_at}/{conversation}', [ConversationController::class, 'show']);
    Route::get('/messages', [MessageController::class, 'showMessages']);

    // Support and tickets
    Route::post('/ticket', [SupportController::class, 'create']);

    // Reports
    Route::get('/store/show/report/{name}/{id}', [ReportController::class, 'create']);
    Route::get('/listing/report/{name?}/{id}', [ReportController::class, 'create']);

    // Cart routes
    Route::get('/{name}/{created_at}/cart', [CartController::class, 'create']);
    Route::post('/{name}/{created_at}/cart', [CartController::class, 'createOrder']);
    Route::patch('/cart/{user}/{created_ta}/{cart}', [CartController::class, 'checkAction']);

    // Promo code route
    Route::post('/apply/promocode', [CartController::class, 'checkPromoInCart']);

    // order info 
    Route::get('/order/{created_at}/{order}', [OrderController::class, 'show']);
    Route::post('/order/{created_at}/{order}', [OrderController::class, 'update']);

    // Notifications 
    Route::post('/notification/{created_at}/{notification}', [NotificationController::class, 'update']);
    Route::get('/notification', [NotificationController::class, 'showNotifications']);
    Route::get('/canary', [GeneralController::class, 'canary']);

    // general
    Route::get('/faq', [FAQController::class, 'create']);
    Route::get('/news', [NewsController::class, 'create']);
    Route::post('/news', [NewsController::class, 'markAsRead']);
    Route::get('/{user}/{created_at}/supports', [SupportController::class, 'showTicket']);
    Route::get('/bugs', [BugController::class, 'create']);
    Route::get('/links', [LinkController::class, 'create']);


    // // user stats
    // Route::get('/account/stats', [UserController::class, 'stats']);

    // user theme
    Route::get('/account/theme', [UserController::class, 'theme']);

    Route::post('/market/welcome/{user}/read', [UserController::class, 'welcome']);

    // User 
    Route::get('/{name}/{created_at}/{action}', [UserController::class, 'show']);

    // User pg key system 2fa verify
    Route::post('/pgp', [GeneralController::class, 'pgpKeySystem']);
    Route::get('/auth/pgp/verify', [UserController::class, 'pgpVerify']);
    Route::post('/auth/pgp/verify', [UserController::class, 'pgpCodeVerify']);
    Route::post('/account/pgp', [GeneralController::class, 'userPgpSystem']);

    /// wallets tests
    Route::get('/{user}/generate/new/xmr/address', [WalletController::class, 'create']);

    // search  
    Route::get('/{name}/{created_at}/listings/search', [SearchController::class, 'quickListtingSearch']);

    Route::get('/{user}/orders/search', [SearchController::class, 'userOrderSearch']);
    Route::get('/{user}/messages/search', [SearchController::class, 'userMessageSearch']);
    Route::get('/{user}/notifications/search', [SearchController::class, 'userNotificationsSearch']);
    Route::get('/{user}/show/product/reviews/search', [SearchController::class, 'productReviewsSearch']);
});
