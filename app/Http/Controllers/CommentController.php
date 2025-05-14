<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Events\CommentCreated;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function insertPage()
    {
        return view('insert');
    }

    public function viewPage()
    {
        return view('comments', [
            'comments' => Comment::all()
        ]);
    }

    public function store(Request $request)
    {
        $comment = Comment::create([
            'message' => $request->message
        ]);

        broadcast(new CommentCreated($comment))->toOthers();

        return response()->json(['status' => 'ok']);
    }
}