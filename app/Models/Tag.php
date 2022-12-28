<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * 定义relation
     */

    public function cafes(){
        return $this->belongsToMany(Cafe::class,'cafes_users_tags','tag_id','cafe_id');
    }

}
