<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->paginate(5);
        return $posts;
        //render view with posts
        //return view('posts.index', compact('posts'));
    }
}