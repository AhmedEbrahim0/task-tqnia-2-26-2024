<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatsController extends Controller
{
    use ResponseTrait;
    public function index(){
        $users=User::count();
        $number_posts=Post::count();
        $number_users_with_zero_posts=User::whereDoesntHave('posts')->get();

        $data=[
            "number_users"=>$users,
            "number_posts"=>$number_posts,
            "number_users_with_zero_posts"=>count($number_users_with_zero_posts)??0,
        ];
        return $this->Response($data,"Data",200);
    }
}
