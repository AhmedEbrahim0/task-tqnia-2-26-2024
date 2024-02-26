<?php

namespace App\Models;

use App\Models\Tags;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="posts";
    protected $guarded=[];
    public function tags()  {
        
        return $this->belongsToMany(Tags::class)->as('tags');
    }
    public function user()  {
        
        return $this->belongsTo(User::class,'user_id')->as('user');
    }
    
    
}
