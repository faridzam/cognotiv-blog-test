<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\{
    User,
    role,
    post,
    post_comment,
    post_react,
};

class BlogController extends Controller
{
    //
    public function index(Request $request){

        // $user = User::find(auth()->user()->id);
        // $role = role::find($user->role_id);

        // dd($searchOnlyPublishedParam);

        $posts = post::query()
        ->when([$request->search, $request->searchOnlyPublished], function($query, $search) {

            $authors = User::query()
            ->when($search, function ($query, $search){
                $query->where('name', 'like', '%'.$search[0].'%');
            })->pluck('id');

            $query
            ->where(
                function($query) use($search){
                    if ($search[1] === "true") {
                        $query->where('isPublished', '=', 1);
                    } else {
                        $query->where('isPublished', '=', 1);
                    }
                }
            )
            ->where(
                function($query) use($search, $authors){
                    $query->where('title','like','%'.$search[0].'%')
                    ->orWhere('meta_title', 'like', '%'.$search[0].'%')
                    ->orWhere('content', 'like', '%'.$search[0].'%')
                    ->OrWhereIn('user_id', $authors);
                }
            );
        })
        ->where('isPublished', '=', 1)
        ->paginate(8)
        ->through(function ($post) {
            $user = User::find($post->user_id);
            $like = post::find($post->id)
            ->reacts()
            ->where('status', 1)
            ->get();
            $dislike = post::find($post->id)
            ->reacts()
            ->where('status', 2)
            ->get();
            $comments = post::find($post->id)
            ->comments()
            ->with('author')
            ->get();
            return [
                'id' => $post->id,
                'title' => $post->title,
                'user' => $user->name,
                'image' => $post->image,
                'content' => $post->content,
                'like' => $like,
                'dislike' => $dislike,
                'comments' => $comments,
            ];
        })->withQueryString();
        $filter = [
            'search' => $request->search ? $request->search : '',
            'searchOnlyPublished' => $request->searchOnlyPublished ? $request->searchOnlyPublished : false,
        ];


        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'posts' => $posts,
            'filter' => $filter
        ]);
    }

    public function likePost(string $id){
        //
        $isExist = post_react::where('post_id', $id)
        ->where('status', 1)
        ->where('user_id', auth()->user()->id)
        ->first();

        if ($isExist === null) {
            $react = new post_react;

            $react->post_id = $id;
            $react->user_id = auth()->user()->id;
            $react->status = 1;
            $react->save();

        } else {
            $react = post_react::where('post_id', $id)
            ->where('status', 1)
            ->where('user_id', auth()->user()->id)
            ->delete();
        }

        return to_route('welcome');
    }
    public function dislikePost(string $id){
        $isExist = post_react::where('post_id', $id)
        ->where('status', 2)
        ->where('user_id', auth()->user()->id)
        ->first();

        if ($isExist === null) {
            $react = new post_react;

            $react->post_id = $id;
            $react->user_id = auth()->user()->id;
            $react->status = 2;
            $react->save();

        } else {
            $react = post_react::where('post_id', $id)
            ->where('status', 2)
            ->where('user_id', auth()->user()->id)
            ->delete();
        }

        return to_route('welcome');
    }
    public function commentPost(string $id){
        //
    }
}
