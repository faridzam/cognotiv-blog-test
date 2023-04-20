<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class ReactionController extends Controller
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        switch ($request->action) {
            case 'like':
                $isExist = post_react::where('post_id', $request->id)
                ->where('status', 1)
                ->where('user_id', auth()->user()->id)
                ->first();

                if ($isExist === null) {
                    $react = new post_react;

                    $react->post_id = $request->id;
                    $react->user_id = auth()->user()->id;
                    $react->status = 1;
                    $react->save();

                } else {
                    $react = post_react::where('post_id', $request->id)
                    ->where('status', 1)
                    ->where('user_id', auth()->user()->id)
                    ->delete();
                }
                break;
            case 'dislike':
                $isExist = post_react::where('post_id', $request->id)
                ->where('status', 2)
                ->where('user_id', auth()->user()->id)
                ->first();

                if ($isExist === null) {
                    $react = new post_react;

                    $react->post_id = $request->id;
                    $react->user_id = auth()->user()->id;
                    $react->status = 2;
                    $react->save();

                } else {
                    $react = post_react::where('post_id', $request->id)
                    ->where('status', 2)
                    ->where('user_id', auth()->user()->id)
                    ->delete();
                }
                break;
            case 'comment':
                $comment = new post_comment;
                $comment->post_id = $request->id;
                $comment->user_id = auth()->user()->id;
                $comment->comment = $request->comment;
                $comment->save();
                break;
            default:
                # code...
                break;
        }
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
