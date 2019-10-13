<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Blog\BlogRequest;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Blog;
use App\Repository\BlogRepository;

class BlogController extends Controller
{
    protected $repository;
    //
/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BlogRepository $repository)
    {
        $this->middleware('auth');
        $this->middleware('isAdmin',['only'=> ['blogDelete']]);
        $this->repository = $repository;
    }
    public function getBlog($id)
    {
        $blog = Blog::where('id',$id)->first();
        if(!$blog){
            return response()->json(['error'=>"404",'message'=>"blog not found"], 404);
        }
        return response()->json($blog);
    }

    public function blogPost(BlogRequest $request)
    {
        $this->validate(request(), [
            "title" => "required",
            "content" => "required"
        ]);
        $blog = $this->repository->create(request()->all());
        return response()->json($blog);
    }

    /**
     * @param $title - the title of the blog
     */
    public function blogUpdate(BlogRequest $request, $id)
    {
        $this->validate(request(), [
            "title" => "required",
            "content" => "required"
        ]);
        $blog = $this->repository->update($id, request()->all());
        return response()->json($blog);        
    }

    public function blogDelete(Request $request, $id)
    {
        $blog = Blog::findOrFail( $id );
        if($blog){
            $blog->delete();
        }else{
            return response()->json(['error' => 'blog not found'], 401);
        }
        return response()->json($blog);
    }

    public function blogRestore(Request $request, $id)
    {
        $blog = Blog::onlyTrashed()
                ->findOrFail( $id );
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
        $Allblogs = Blog::with(['createdBy','updatedBy'])->orderPaginate('updated_at', $blogsOrder ? "desc" : "asc", $request->input('per_page'));
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
