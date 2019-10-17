<?php

namespace App\Http\Controllers\Api\Newsletter;

use App\Http\Controllers\Controller;
use App\Model\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['createNewsletter']]);
        $this->middleware('isAdmin',['only'=> ['getAllNewsletter']]);        
    }

    public function getAllNewsletter(){
        $order_by = explode(":",request("order_by", "updated_at:desc"));
        $where = explode(":",request("where", "email:"));

        $newsletter = Newsletter::where($where[0],'like', '%' . $where[1])
            ->where($where[0],'like', $where[1] . '%')            
            ->orderBy($order_by[0], count($order_by) != 2 ? "desc" : $order_by[1])
            ->paginate(request()->input('per_page'));

        return response()->json($newsletter);
    }

    public function createNewsletter(){
        $newsletter = new Newsletter();
        $news = $newsletter->create(request()->all());
        return response()->json($news);
    }
}
