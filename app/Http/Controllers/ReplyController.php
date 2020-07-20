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
        if (! $post) {
            abort(401);
        } else {
            $reply = new Reply();
            $reply->reply_text = $request->get('reply_text');
            $reply->user_id = Auth::user()->id;
            $reply->message_id = $message->id;
            $message->replies()->save($reply);

            $new_reply = Reply::where('id', $reply->id)->with('reply_user')->first();

            return response($new_reply, 201);
        }
    }

    /**
     * メッセージ詳細の取得
     * @params Post $post
     * @params Message $message
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Message $message)
    {
        if (! $post) {
            abort(401);
        } else {
            $message = Message::where('id', $message->id)->with(['author', 'replies.reply_user'])->first();
        }
        return $message ?? abort(404);
    }

    /**
     * メッセージ募集の削除
     * @params string $id
     * @return Reply
     */
    public function delete(string $id)
    {
        $reply = Reply::where('id', $id)->first();

        if ((int) $reply->user_id !==  Auth::user()->id || ! $reply) {
            abort(401);
        } else {
            $reply->delete();
        }
    }
}
