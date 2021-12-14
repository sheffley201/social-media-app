<?php

use Illuminate\Support\Facades\Route;
// Get input from requests
use Illuminate\Http\Request;
//Model for posts
use App\Models\Post;
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
            SELECT p.id, p.body, p.user_id, u.name
            FROM posts p
            JOIN users u
            ON p.user_id = u.id
            ORDER BY p.created_at DESC
        ');
        return view('dashboard', ['posts' => $posts]);
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

    Route::post('/delete-post', function(Request $request) {
        $postID = $request->post_id;
        Post::where('id', $postID)->firstorfail()->delete();
        return redirect('/dashboard');
    })->name('delete-post');

    Route::get('/profile', function() {
        $user_id = Auth::user()->id;
        $posts = DB::select("
            SELECT body, id
            FROM posts
            WHERE user_id = '{$user_id}'
            ORDER BY created_at DESC
        ");

        return view('profile', ['posts' => $posts]);
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
