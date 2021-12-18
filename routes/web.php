<?php

use Illuminate\Support\Facades\Route;
// Get input from requests
use Illuminate\Http\Request;
//Model for posts
use App\Models\Post;
//Model for likes
use App\Models\Like;
// Added to query database
use Illuminate\Support\Facades\DB;
// Authentication
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', function () {
        $posts = DB::select('
            SELECT p.id, p.body, p.user_id, u.name, COUNT(l.id) likes
            FROM posts p
            JOIN users u
            ON p.user_id = u.id
            LEFT JOIN likes l
            ON p.id = l.post_id
            GROUP BY p.id, p.body, p.user_id, u.name
            ORDER BY p.created_at DESC
        ');

        $userLikes = DB::select("
            select post_id
            from likes
            where user_id = ".Auth::user()->id."
        ");

        $likeArr = [];

        foreach ($userLikes as $userLike) {
            array_push($likeArr, $userLike->post_id);
        };

        return view('dashboard', ['posts' => $posts])
            ->with('likeArr', $likeArr);
    })->name('dashboard');

    Route::post('/create-post', function(Request $request) {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'body' => 'required|max:1000',
        ]);

        $post = new Post;
        $post->user_id = $request->user_id;
        $post->body = $request->body;
        $post->save();

        return redirect('/dashboard');
    })->name('create-post');

    Route::post('/like-post', function(Request $request) {
        $like = new Like;
        $like->user_id = Auth::id();
        $like->post_id = $request->post_id;
        $like->save();

        return redirect('/dashboard');
    })->name('like-post');

    Route::post('/unlike', function() {
        $like = Like::where('user_id', Auth::id())
            ->where('post_id', request()->post_id)
            ->first();

        $like->delete();

        return redirect('/dashboard');
    })->name('unlike');

    Route::post('/delete-post', function(Request $request) {
        $postID = $request->post_id;
        Post::where('id', $postID)->firstorfail()->delete();
        return redirect('/dashboard');
    })->name('delete-post');

    Route::get('/profile', function() {
        $user_id = Auth::user()->id;
        $posts = DB::select("
            SELECT p.body, p.id, COUNT(l.id) likes
            FROM posts p
            LEFT JOIN likes l
            ON p.id = l.post_id
            WHERE p.user_id = '{$user_id}'
            GROUP BY p.id, p.body
            ORDER BY p.created_at DESC
        ");

        $userLikes = DB::select("
            select post_id
            from likes
            where user_id = ".Auth::user()->id."
        ");

        $likeArr = [];

        foreach ($userLikes as $userLike) {
            array_push($likeArr, $userLike->post_id);
        };

        return view('profile', ['posts' => $posts])
            ->with('likeArr', $likeArr);
    })->name('profile');

    Route::post('/delete-post-profile', function(Request $request) {
        $postID = $request->post_id;
        Post::where('id', $postID)->firstorfail()->delete();
        return redirect('/profile');
    })->name('delete-post-profile');

    Route::post('/create-post-profile', function(Request $request) {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'body' => 'required|max:1000',
        ]);

        $post = new Post;
        $post->user_id = $request->user_id;
        $post->body = $request->body;
        $post->save();

        return redirect('/profile');
    })->name('create-post-profile');
});

require __DIR__.'/auth.php';
