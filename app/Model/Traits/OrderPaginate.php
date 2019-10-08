<?php

namespace App\Model\Traits;

trait OrderPaginate {
    
  public function scopeOrderPaginate($query, $order, $orderType, $limit) {
      return $query->orderBy($order, $orderType)->paginate($limit);
  }

}