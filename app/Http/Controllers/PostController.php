<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Models\{
    User,
    post,
};

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Posts/CreatePost');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'meta_title' => 'required',
            'slug' => 'required',
            'content' => 'required',
            // 'image' => 'required',
        ]);

        $post = new post;

        if ($request->image) {
            $file_name = time().'_'.$request->image->getClientOriginalName();
            $file_path = $request->image->storeAs('uploads', $file_name, 'public');

            $post->user_id = auth()->user()->id;
            $post->title = $request->title;
            $post->meta_title = $request->meta_title;
            $post->slug = $request->slug;
            $post->content = $request->content;
            $post->isPublished = $request->isPublished;
            $post->isActive = 1;
            $post->image = '/storage/'.$file_path;
            $post->published_at = $request->isPublished === 1 ? Carbon::now() : null;
            $post->save();
        } else {
            $file_name = time().'_'.$request->image->getClientOriginalName();
            $file_path = $request->image->storeAs('uploads', $file_name, 'public');

            $post->user_id = auth()->user()->id;
            $post->title = $request->title;
            $post->meta_title = $request->meta_title;
            $post->slug = $request->slug;
            $post->content = $request->content;
            $post->isPublished = $request->isPublished;
            $post->isActive = 1;
            $post->image = null;
            $post->published_at = $request->isPublished === 1 ? Carbon::now() : null;
            $post->save();
        }



        return to_route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $post = post::find($id);
        return Inertia::render('Posts/EditPost', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'title' => 'required',
            'meta_title' => 'required',
            'slug' => 'required',
            'content' => 'required',
            // 'image' => 'required',
        ]);

        $post = post::find($id);
        $post->title = $request->title;
        $post->meta_title = $request->meta_title;
        $post->slug = $request->slug;
        $post->content = $request->content;
        $post->isPublished = $request->isPublished;
        $post->isActive = 1;
        if ($request->image !== null) {
            $file_name = time().'_'.$request->image->getClientOriginalName();
            $file_path = $request->image->storeAs('uploads', $file_name, 'public');
            $post->image = '/storage/'.$file_path;
        }
        $post->save();
        return to_route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $post = post::find($id);
        if ($post->image !== null) {
            // $image_path = public_path().'/'.$post->image;
            // unlink($image_path);
            $post->delete();
        } else {
            $post->delete();
        }
    }
}
