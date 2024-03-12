<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\Category;
use App\Models\NewsStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use function PHPUnit\Framework\returnSelf;

class NewsController extends Controller
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
    public function create()
    {
                //check if the user has 2fa enable and if they has verified it else redirect them to /auth/pgp/verify
                if (auth()->user()->twofa_enable == 'yes' && !session('pgp_verified')) {
                    return redirect('/auth/pgp/verify');
                }
                
        $user = auth()->user();
        return view('User.allnews', [
            // 'news' => News::where('created_at')->get();
            'user' => $user,
            'parentCategories' => Category::whereNull('parent_category_id')->get(),
            'subCategories' => Category::whereNotNull('parent_category_id')->get(),
            'categories' => Category::all(),
            'icon' => GeneralController::encodeImages(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }

    public function markAsRead(Request $request){
        $request->validate([
            'news' => 'required|min:64|max:256'
        ]);
    
        $news = Crypt::decrypt($request->news);
        $user = auth()->user();
    
        // Check if the user hasn't already read the news
        if (!$user->newsStatuses->contains('news_id', $news)) {
            $readNews = new NewsStatus();
            $readNews->user_id = $user->id;
            $readNews->news_id = $news;
            $readNews->save();
        }
    
        return redirect()->back();
    }
    
}
