<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * 募集履歴の一覧
     */
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->with(['user', 'photos'])->orderBy('created_at', 'desc')->get();
        return $posts;
    }
}
