<?php

namespace App\Repository;

use App\Model\Blog;

class BlogRepository extends Repository
{
  protected $model;

  public function __construct()
  {
    $this->model = new Blog();
  }

  public function find($id, $keys = [])
  {
    return $this->model->findOrFail($id,$keys);
  }

  public function create($data = [])
  {
    ///method 1///
    /*Note
    * Model doesn't need to set properties to fillable
    * Need to make setter for each properties
    * Need to check nullable properties
    */
    // $blog = new Blog();
    // $blog->title = $request->title;
    // $blog->content = $request->content;
    // if($request->type){
    //     $blog->type = $request->type;
    // }
    // $blog->created_by = $user->id;
    // $blog->updated_by = $user->id;
    // $blog->save();

    ///method 2///
    /*Note
    * Model need to set properties to fillable
    * No need to make setter for each properties
    * No Need to check nullable properties
    */
    // $user = auth()->user();
    // $data = request()->all();
    // $data["title"] = $data->title;
    // $data["content"] = $data->content;
    // $data["type"] = $data->type;
    // $data["created_by"] = $user->id;
    // $data["updated_by"] = $user->id;

    ///method 3///
    return $this->model->create($data)->fresh();
  }

  public function update($id, $data = null)
  {
    ///method 1///
    // $user = auth()->user();
    // $userTable = new User();
    // $blogTable = new Blog();
    // $blog = $blogTable::find( $id );
    // $blog->title = $request->title;
    // $blog->content = $request->content;
    // if($request->type){
    //     $blog->type = $request->type;
    // }
    // $blog->updated_by = $user->id;
    // $blog->save();
    
    ///method 2///
    $blog = Blog::findOrFail($id);
    $blog->update($data);
    return $blog->fresh();
  }
}
