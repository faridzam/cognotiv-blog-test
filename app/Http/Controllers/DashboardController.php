<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\{
    User,
    role,
    post,
};

class DashboardController extends Controller
{
    //
    public function index(Request $request) {
        $user = User::find(auth()->user()->id);
        $role = role::find($user->role_id);

        if ($request->search !== '') {
            if ($role->id === 1) {

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
                                $query;
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
                })->paginate(8)->through(function ($post) {
                    $user = User::find($post->user_id);
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'user' => $user->name,
                        'image' => $post->image,
                        'content' => $post->content,
                    ];
                })->withQueryString();
                $filter = [
                    'search' => $request->search ? $request->search : '',
                    'searchOnlyPublished' => $request->searchOnlyPublished ? $request->searchOnlyPublished : false,
                ];
            } else {
                $posts = post::query()
                ->when([$request->search, $request->searchOnlyPublished], function($query, $search) use($user){

                    $query
                    ->where(
                        function($query) use($search){
                            if ($search[1] === "true") {
                                $query->where('isPublished', '=', 1);
                            } else {
                                $query;
                            }
                        }
                    )
                    ->where('user_id', '=', $user->id)
                    ->where(
                        function($query) use($search){
                            $query->where('title','like','%'.$search[0].'%')
                            ->orWhere('meta_title', 'like', '%'.$search[0].'%')
                            ->orWhere('content', 'like', '%'.$search[0].'%');
                        }
                    );
                })
                // ->when($request->searchOnlyPublished, function($query, $searchOnlyPublished) use($user, $searchOnlyPublishedParam){
                    // $query->where(
                    //     function($query) use($searchOnlyPublishedParam){
                    //         if ($searchOnlyPublishedParam) {
                    //             $query->where('isPublished', '=', 1);
                    //         } else {
                    //             //
                    //         }
                    //     }
                    // );
                // })
                ->where('user_id', $user->id)
                ->paginate(8)
                ->through(function ($post) {
                    $user = User::find($post->user_id);
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'user' => $user->name,
                        'image' => $post->image,
                        'content' => $post->content,
                    ];
                })
                ->withQueryString();
                $filter = [
                    'search' => $request->search ? $request->search : '',
                    'searchOnlyPublished' => $request->searchOnlyPublished ? $request->searchOnlyPublished : false,
                ];
            }
        } else {
            if ($role->id === 1) {
                $posts = post::paginate(8)->through(function ($post) {
                    $user = User::find($post->user_id);
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'user' => $user->name,
                        'image' => $post->image,
                        'content' => $post->content,
                    ];
                });
                $filter = [
                    'search' => $request->search ? $request->search : '',
                    'searchOnlyPublished' => $request->searchOnlyPublished ? $request->searchOnlyPublished : false,
                ];
            } else {
                $posts = post::where('user_id', $user->id)->paginate(8)->through(function ($post) {
                    $user = User::find($post->user_id);
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'user' => $user->name,
                        'image' => $post->image,
                        'content' => $post->content,
                    ];
                });
                $filter = [
                    'search' => $request->search ? $request->search : '',
                    'searchOnlyPublished' => $request->searchOnlyPublished ? $request->searchOnlyPublished : false,
                ];
            }

        }
        return Inertia::render('Dashboard', [
            'posts' => $posts,
            'filter' => $filter
        ]);
    }
}
