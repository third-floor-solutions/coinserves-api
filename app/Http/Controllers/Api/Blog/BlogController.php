<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Blog\BlogRequest;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Blog;
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
        $this->middleware('isAdmin',['only'=> ['blogDelete']]);
    }

    public function blogPost(BlogRequest $request)
    {
        $user = auth()->user();
        // $blog = new Blog();
        // $blog->title = $request->title;
        // $blog->content = $request->content;
        // if($request->type){
        //     $blog->type = $request->type;
        // }
        // $blog->poster_id = $user->id;
        // $blog->save(); 

        $blog = Blog::create($request->all());

        $blog = $blog::where('id', $blog->id)->first();
        $blog->user = $user::where('id', $blog->poster_id)->first();
        return response()->json($blog);
    }

    /**
     * @param $title - the title of the blog
     */
    public function blogUpdate(BlogRequest $request, $id)
    {
        $user = auth()->user();
        $userTable = new User();
        // $blogTable = new Blog();
        // $blog = $blogTable::find( $id );
        // $blog->title = $request->title;
        // $blog->content = $request->content;
        // if($request->type){
        //     $blog->type = $request->type;
        // }
        // $blog->update_poster_id = $user->id;
        // $blog->save();
        $blog = Blog::findOrFail($id);
        $blog->update(request()->all());
        
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
        $this->validate(request(), [
            "desc" => "int"
            // "desc" => "required|int"
        ]);
        $blogsOrder = request("desc", 0);
        $Allblogs = Blog::orderPaginate('updated_at', $blogsOrder ? "desc" : "asc", $request->input('per_page'));
        return response()->json($Allblogs);
    }

    public function getAllArchivedBlogs(Request $request)
    {   
        $this->validate(request(), [
            "desc" => "int"
        ]);
        $blogsOrder = request("desc", 0);
        $Allblogs = Blog::onlyTrashed()->orderPaginate('updated_at', $blogsOrder ? "desc" : "asc", $request->input('per_page'));
        return response()->json($Allblogs);
    }
}
