<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->with(['user', 'likes'])->paginate(10);
                            // latest()
        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' =>$post
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        // Post::create([
        //     'user_id' => auth()->user()->id(),
        //     'body' => $request->body 
        // ]);
        $request->user()->posts()->create($request->only('body'));
        return back();
    }

    public function destroy(Post $post)
    {
        // if(!$post->ownedBy(auth()->user())){
        //     dd('no');
        // }
        // dd($post);
        $this->authorize('delete', $post);
        $post->delete();
        return back();
    }
}
