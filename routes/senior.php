<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeniorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/senior/staff/kick/{user}/out', [UserController::class, 'kickout']);
Route::get('/senior/staff/{user}/show/theme', [UserController::class, 'theme']);

Route::get('/senior/staff/{user}/show/{action?}', [SeniorController::class, 'index']);

// change settings
Route::post('/senior/staff/{user}/update/settings', [SeniorController::class, 'settings']);


// notification
Route::post('/senior/staff/{user}/notification/{created_at}/{notification}', [NotificationController::class, 'update']);


// messages /senior/staff/ghost/show/messages/1706650508/14
Route::get('/senior/staff/{user}/show/messages/{created_at}/{conversation}', [SeniorController::class, 'showMessages']);
Route::post('/senior/staff/{user}/show/messages/{created_at}/{conversation}', [MessageController::class, 'seniorModUser']);
Route::post('/senior/staff/{user}/conversation/archive/{created_at}/{conversation}', [ConversationController::class, 'seniorArchiveConv']);

// flags reviews 
Route::post('/senior/staff/{user}/flag/review/{review}', [AdminController::class, 'flagReview']);


// User Users
Route::get('/senior/staff/show/user/{created_at}/{user}', [SeniorController::class, 'user']);
Route::post('/senior/staff/show/user/{created_at}/{user}', [SeniorController::class, 'banUnbanUser']);


// Store Stores
Route::get('/senior/staff/show/new store/{created_at}/{new_store}', [SeniorController::class, 'new_store']);
Route::post('/senior/staff/show/new store/{created_at}/{new_store}', [SeniorController::class, 'approveDeclineStore']);
Route::get('/senior/staff/show/store/reviews/{created_at}/{store}', [SeniorController::class, 'showStoreReviews']);

Route::get('/senior/staff/show/store/{created_at}/{store}', [SeniorController::class, 'store']);
Route::post('/senior/staff/show/store/{created_at}/{store}', [SeniorController::class, 'banUnban']);

// products
Route::get('/senior/staff/show/product/{created_at}/{product}', [SeniorController::class, 'product']);
Route::post('/senior/staff/show/product/{created_at}/{product}', [SeniorController::class, 'approveReject']);


// product reviews
Route::get('/senior/staff/show/product/reviews/{created_at}/{product}', [SeniorController::class, 'showProductReviews']);

// unauthorize
Route::get('/senior/staff/show/unauthorize/{created_at}/{unauthorize}', [SeniorController::class, 'unauthorizeAccess']);


// Support ticket
Route::post('/senior/staff/{user}/show/support', [SeniorController::class, 'joinSupport']);
Route::get('/senior/staff/show/ticket/{created_at}/{conversation}', [SeniorController::class, 'supportTicket']);
Route::post('/senior/staff/show/ticket/{created_at}/{conversation}', [MessageController::class, 'seniorModUser']);


// mod mail messages
Route::post('/senior/staff/{user}/start/new/message', [MessageController::class, 'createModMailMessage']);

// Disputes
Route::get('/senior/staff/{user}/show/dispute/{created_at}/{dispute}', [DisputeController::class, 'disputeShow']);
Route::post('/senior/staff/{user}/do/dispute/{created_at}/{dispute}', [DisputeController::class, 'disputeDo']);

// faq
Route::post('/senior/staff/{user}/new/faq', [SeniorController::class, 'newFAQ']);

// canary upadte
Route::post('/senior/staff/{user}/update/canary', [SeniorController::class, 'updateCanary']);


// create new news
Route::post('/senior/staff/{user}/new/news', [SeniorController::class, 'addNews']);

// captcha
Route::get('/senior/staff/{user}/captcha', [GeneralController::class, 'captcha']);

// pgp key system 
Route::post('/senior/staff/{user}/do/pgp', [GeneralController::class, 'userPgpSystem']);
Route::post('/senior/staff/{user}/verify/pgp', [GeneralController::class, 'pgpKeySystem']);

// Store waiver
Route::get('/senior/staff/{user}/show/waiver/{created_at}/{waiver}', [SeniorController::class, 'showWaiver']);
Route::post('/senior/staff/{user}/show/waiver/{created_at}/{waiver}', [SeniorController::class, 'updateWaiver']);

/// wallets tests
Route::get('/senior/staff/{user}/generate/new/xmr/address', [WalletController::class, 'create']);
// withdraws
Route::post('/senior/staff/{user}/wallet/withdraw', [WalletController::class, 'withdraw']);

// 2fa pgp..........
Route::get('/auth/staff/senior/pgp/verify', [UserController::class, 'pgpVerify']);
Route::post('/auth/staff/senior/pgp/verify', [UserController::class, 'pgpCodeVerify']);

// market functions
Route::post('/senior/staff/{user}/show/functions', [AdminController::class, 'marketFunctions']);

// message modmail
Route::get('/senior/staff/{user}/start/new/message', [SeniorController::class, 'modMail']);

// bugs report
Route::post('/senior/staff/{user}/show/bugs', [AdminController::class, 'bugs']);

// search routes
Route::get('/senior/staff/{user}/show/users/search', [SearchController::class, 'staffSearchUsers']);
Route::get('/senior/staff/{user}/show/newstores/search', [SearchController::class, 'staffSearchNewStores']);
Route::get('/senior/staff/{user}/show/stores/search', [SearchController::class, 'staffSearchStores']);
Route::get('/senior/staff/{user}/show/products/search', [SearchController::class, 'staffSearchProducts']);
Route::get('/senior/staff/{user}/show/disputes/search', [SearchController::class, 'staffSearchDisputes']);
Route::get('/senior/staff/{user}/show/supports/search', [SearchController::class, 'staffSearchSupports']);
Route::get('/senior/staff/{user}/show/reports/search', [SearchController::class, 'staffSearchReports']);
Route::get('/senior/staff/{user}/show/waivers/search', [SearchController::class, 'staffSearchWaivers']);
Route::get('/senior/staff/{user}/show/unauthorizes/search', [SearchController::class, 'staffSearchUnauthorizes']);
Route::get('/senior/staff/{user}/show/reviews/search', [SearchController::class, 'adminReviewsSearch']);
Route::get('/senior/staff/{user}/show/product/reviews/search', [SearchController::class, 'productReviewsSearch']);

// message search and notification
Route::get('/senior/staff/{user}/messages/show/search',  [SearchController::class, 'userMessageSearch']);
Route::get('/senior/staff/{user}/notifications/show/search',  [SearchController::class, 'userNotificationsSearch']);
