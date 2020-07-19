<?php

namespace App\Http\Controllers;

use App\Post;
use App\Message;
use App\User;
use App\Http\Requests\StoreReplies;
use App\Reply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    /**
     * メッセージ投稿
     * @params Post $post
     * @params Message $message
     * @param StoreReplies $request
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post, Message $message, StoreReplies $request)
    {
        $id = Auth::user()->id;
        if ((int) $post->user_id === $id) {
            $reply = new Reply();
            $reply->reply_text = $request->get('reply_text');
            $reply->user_id = $id;
            $reply->message_id = $message->id;
            $message->reply()->save($reply);

            $new_reply = Reply::where('id', $reply->id)->with('reply_user')->first();

            return response($new_reply, 201);
        } else {
            abort(401);
        }
    }
}
