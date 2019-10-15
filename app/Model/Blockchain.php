<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blockchain extends Model
{
    use SoftDeletes;

    protected $table = 'blockchains';
    
    protected $fillable = [
        'cnsrv_n_tx'
    ];

        /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'wallet_address' => ['required', 'string', 'max:255', 'unique:blockchains']
        ];
    }
}
