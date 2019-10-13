<?php

namespace App\Model;

use App\Model\Traits\OrderPaginate;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes, OrderPaginate;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'type'
    ];

    /******************************************
     * MUTATORS
     ******************************************/

    // public function setCreatedByAttribute() {
    //     $user = auth()->user();
    //     if(!is_null($user))
    //         $this->attributes["created_by"] = $user->id;
    // }

    // public function setUpdatedByAttribute() {
    //     $user = auth()->user();
    //     if(!is_null($user))
    //         $this->attributes["updated_by"] = $user->id;
    // }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();

            if(!isset($post->type))
                $post->type = "story";

            $user = auth()->user();
            if(!is_null($user)) {
                $post->created_by = $user->id;
                $post->updated_by = $user->id;
            }
        });

        static::updating(function ($post) {
            $user = auth()->user();
            if(!is_null($user)) {
                $post->updated_by = $user->id;
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
}
