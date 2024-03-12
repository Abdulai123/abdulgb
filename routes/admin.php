<?php

use App\Http\Controllers\DisputeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeniorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Models\Conversation;
use Illuminate\Support\Facades\Route;





Route::get('/whales/admin/kick/{user}/out', [UserController::class, 'kickout']);
Route::get('/whales/admin/{user}/show/theme', [UserController::class, 'theme']);

Route::get('/whales/admin/{user}/show/{action?}', [AdminController::class, 'index']);

/// wallets tests
Route::get('/whales/admin/{user}/generate/new/xmr/address', [WalletController::class, 'create']);
// withdraws
Route::post('/whales/admin/{user}/show/withdraw', [WalletController::class, 'withdraw']);

// 2fa pgp..........
Route::get('/auth/whales/admin/pgp/verify', [UserController::class, 'pgpVerify']);
Route::post('/auth/whales/admin/pgp/verify', [UserController::class, 'pgpCodeVerify']);

// User Users
Route::get('/whales/admin/show/user/{created_at}/{user}', [AdminController::class, 'user']);
Route::post('/whales/admin/show/user/{created_at}/{user}', [AdminController::class, 'banUnbanUser']);

// change settings
Route::post('/whales/admin/{user}/update/settings', [SeniorController::class, 'settings']);

// Store Stores
Route::get('/whales/admin/show/new store/{created_at}/{new_store}', [AdminController::class, 'new_store']);
Route::post('/whales/admin/show/new store/{created_at}/{new_store}', [AdminController::class, 'approveDeclineStore']);
Route::get('/whales/admin/show/store/reviews/{created_at}/{store}', [AdminController::class, 'showStoreReviews']);

Route::get('/whales/admin/show/store/{created_at}/{store}', [AdminController::class, 'store']);
Route::post('/whales/admin/show/store/{created_at}/{store}', [SeniorController::class, 'banUnban']);
// Route::get('/whales/admin/show/product/{created_at}/{product}', [AdminController::class, 'product']);

// order
Route::get('/whales/admin/show/order/{created_at}/{order}', [AdminController::class, 'order']);

// rules
Route::post('/whales/admin/{user}/show/rules', [AdminController::class, 'rules']);

// notification types 
Route::post('/whales/admin/{user}/show/notifications_types', [AdminController::class, 'notificationTypeEdit']);

// Support ticket
Route::post('/whales/admin/{user}/show/support', [AdminController::class, 'joinSupport']);
Route::get('/whales/admin/show/ticket/{created_at}/{conversation}', [AdminController::class, 'supportTicket']);
Route::post('/whales/admin/show/ticket/{created_at}/{conversation}', [SeniorController::class, 'seniorModUser']);


// messages
Route::get('/whales/admin/{user}/show/messages/{created_at}/{conversation}', [AdminController::class, 'showMessages']);
Route::post('/whales/admin/{user}/show/messages/{created_at}/{conversation}', [MessageController::class, 'seniorModUser']);
Route::post('/whales/admin/{user}/conversation/archive/{created_at}/{conversation}', [ConversationController::class, 'seniorArchiveConv']);


// mod mail messages
Route::post('/whales/admin/{user}/start/new/message', [MessageController::class, 'createModMailMessage']);
Route::get('/whales/admin/{user}/start/new/message', [AdminController::class, 'modsMessage']);

// bugs report
Route::post('/whales/admin/{user}/show/bugs', [AdminController::class, 'bugs']);

// payout in escrow
Route::post('/whales/admin/{user}/show/escrows', [AdminController::class, 'payout']);
Route::post('/whales/admin/{user}/show/payout', [AdminController::class, 'pay']);

// notification
Route::post('/whales/admin/notification/{created_at}/{notification}', [NotificationController::class, 'update']);

// mirrors
Route::post('/whales/admin/{user}/show/mirrors', [AdminController::class, 'mirror']);
Route::get('/whales/admin/{user}/show/mirrors', [AdminController::class, 'mirrors']);

// products
Route::get('/whales/admin/show/product/{created_at}/{product}', [AdminController::class, 'product']);
Route::post('/whales/admin/show/product/{created_at}/{product}', [SeniorController::class, 'approveReject']);


// product
Route::get('/whales/admin/show/product/reviews/{created_at}/{product}', [AdminController::class, 'showProductReviews']);

// unauthorize
Route::get('/whales/admin/show/unauthorize/{created_at}/{unauthorize}', [SeniorController::class, 'unauthorizeAccess']);

// Disputes /whales/admin//do/dispute/1706802825/1
Route::get('/whales/admin/{user}/show/dispute/{created_at}/{dispute}', [DisputeController::class, 'disputeShow']);
Route::post('/whales/admin/{user}/do/dispute/{created_at}/{dispute}', [DisputeController::class, 'disputeDo']);



// Store waiver
Route::get('/whales/admin/{user}/show/waiver/{created_at}/{waiver}', [AdminController::class, 'showWaiver']);
Route::post('/whales/admin/{user}/show/waiver/{created_at}/{waiver}', [AdminController::class, 'updateWaiver']);


// faq
Route::post('/whales/admin/{user}/new/faq', [SeniorController::class, 'newFAQ']);

// canary upadte
Route::post('/whales/admin/{user}/update/canary', [SeniorController::class, 'updateCanary']);

// create new news
Route::post('/whales/admin/{user}/new/news', [SeniorController::class, 'addNews']);

// market functions
Route::post('/whales/admin/{user}/show/functions', [AdminController::class, 'marketFunctions']);

// delete conversation
Route::post('/whales/admin/{user}/show/conversations', [AdminController::class, 'deteleConversation']);

// flags reviews 
Route::post('/whales/admin/{user}/flag/review/{review}', [AdminController::class, 'flagReview']);

// add new server or edit
Route::post('/whales/admin/{user}/show/servers', [AdminController::class, 'server']);

// pgp key system 
Route::post('/whales/admin/{user}/do/pgp', [GeneralController::class, 'userPgpSystem']);
Route::post('/whales/admin/{user}/verify/pgp', [GeneralController::class, 'pgpKeySystem']);



// search routes
Route::get('/whales/admin/{user}/show/users/search', [SearchController::class, 'staffSearchUsers']);
Route::get('/whales/admin/{user}/show/newstores/search', [SearchController::class, 'staffSearchNewStores']);
Route::get('/whales/admin/{user}/show/stores/search', [SearchController::class, 'staffSearchStores']);
Route::get('/whales/admin/{user}/show/products/search', [SearchController::class, 'staffSearchProducts']);
Route::get('/whales/admin/{user}/show/disputes/search', [SearchController::class, 'staffSearchDisputes']);
Route::get('/whales/admin/{user}/show/supports/search', [SearchController::class, 'staffSearchSupports']);
Route::get('/whales/admin/{user}/show/reports/search', [SearchController::class, 'staffSearchReports']);
Route::get('/whales/admin/{user}/show/waivers/search', [SearchController::class, 'staffSearchWaivers']);
Route::get('/whales/admin/{user}/show/unauthorizes/search', [SearchController::class, 'staffSearchUnauthorizes']);
Route::get('/whales/admin/{user}/show/orders/search', [SearchController::class, 'adminOrdersSearch']);
Route::get('/whales/admin/{user}/show/carts/search', [SearchController::class, 'adminCartsSearch']);
Route::get('/whales/admin/{user}/show/conversations/search', [SearchController::class, 'adminConversationsSearch']);
Route::get('/whales/admin/{user}/show/escrows/search', [SearchController::class, 'adminEscrowsSearch']);
Route::get('/whales/admin/{user}/show/coupons/search', [SearchController::class, 'adminCouponsSearch']);
Route::get('/whales/admin/{user}/show/reviews/search', [SearchController::class, 'adminReviewsSearch']);
Route::get('/whales/admin/{user}/show/product/reviews/search', [SearchController::class, 'productReviewsSearch']);

Route::get('/whales/admin/{user}/show/wallets/search', [SearchController::class, 'adminWalletsSearch']);



// message search and notification
Route::get('/whales/admin/{user}/messages/show/search',  [SearchController::class, 'userMessageSearch']);
Route::get('/whales/admin/{user}/notifications/show/search',  [SearchController::class, 'userNotificationsSearch']);
