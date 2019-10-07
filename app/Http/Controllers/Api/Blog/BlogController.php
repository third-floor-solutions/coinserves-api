<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Blog\BlogRequest;
use Illuminate\Http\Request;
use App\User;
use App\Blog;
use App\BlogResults;
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

    public function blogPost(BlogRequest $request)
    {
        $user = auth()->user();
        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        if($request->type){
            $blog->type = $request->type;
        }
        $blog->poster_id = $user->id;
        $blog->save(); 

        $blog = $blog::where('id', $blog->id)->first();
        $blog->user = $user::where('id', $blog->poster_id)->first();
        return response()->json($blog);
    }

    public function blogUpdate(BlogRequest $request, $id)
    {
        $user = auth()->user();
        $userTable = new User();
        $blogTable = new Blog();
        $blog = $blogTable::find( $id );
        $blog->title = $request->title;
        $blog->content = $request->content;
        if($request->type){
            $blog->type = $request->type;
        }
        $blog->update_poster_id = $user->id;
        $blog->save();
        
        $blog->user = $userTable::where('id', $blog->poster_id)->first();
        $blog->update_user = $userTable::where('id', $blog->update_poster_id)->first();
        return response()->json($blog);        
    }

    public function blogDelete(Request $request, $id)
    {
        $blogTable = new Blog();
        $blog = $blogTable::find( $id );
        if($blog){
            $blog->delete();
        }else{
            return response()->json(['error' => 'blog not found'], 401);
        }
        return response()->json($blog);
    }

    public function blogRestore(Request $request, $id)
    {
        $blogTable = new Blog();
        $blog = $blogTable::onlyTrashed()
                ->find( $id );
        if($blog){
            $blog->restore();
        }else{
            return response()->json(['error' => 'blog not found'], 401);
        }
        return response()->json($blog);
    }

    public function getAllBlogs(Request $request)
    {   
        $blogTable = new Blog();
        $blogs = new BlogResults();
        $blogsOrder = 'desc';
        if ($request->has('desc')) {
            if(!$request->input('desc')){
                $blogsOrder = 'asc';
            }
        }
        $Allblogs = $blogTable::orderBy('updated_at', $blogsOrder)
                        ->get();
        $blogCount = $blogTable::count();
        $blogs -> total_count = $blogCount;
        $blogs -> blogs = $Allblogs;
        return response()->json($blogs);
    }

    public function getAllArchivedBlogs(Request $request)
    {   
        $sample = new Blog();
        $blogs = new BlogResults();
        $blogsOrder = 'desc';
        if ($request->has('desc')) {
            if(!$request->input('desc')){
                $blogsOrder = 'asc';
            }
        }
        $Allblogs = $sample::onlyTrashed()
                        ->orderBy('updated_at', $blogsOrder)
                        ->get();
        $blogCount = $sample::onlyTrashed()
                        ->count();
        $blogs -> total_count = $blogCount;
        $blogs -> blogs = $Allblogs;
        return response()->json($blogs);
    }
}
