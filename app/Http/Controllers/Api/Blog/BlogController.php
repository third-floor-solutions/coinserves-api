<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;

class BlogController extends Controller
{
    //
/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function blogpost(Request $request)
    {
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->save(); 

        // Todo send mail
        return $blog;
    }
}
