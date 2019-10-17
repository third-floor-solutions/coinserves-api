<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = ['email'];
    
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ];
    }
}
