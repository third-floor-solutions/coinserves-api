<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class Repository{
  protected $model;

  public function __construct()
  {
    $this->model = new Model(); 
  }

  public function find($id, $keys = [])
  {
    return $this->model->findOrFail($id,$keys);
  }

  public function create($data = [])
  {
    return $this->model->create($data);
  }

  public function update($id, $data = null)
  {
    return $this->model->update($data);
  }

}