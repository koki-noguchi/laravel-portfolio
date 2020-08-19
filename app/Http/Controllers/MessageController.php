<?php

namespace App\Http\Controllers;

use App\Post;
use App\Message;
use App\User;
use App\Http\Requests\StoreMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * メッセージ投稿
     * @param Post $post
     * @param StoreMessage $request
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post, StoreMessage $request)
    {
        if ($post->messages->count() >= $post->max_number) {
            abort(401);
            return;
        }

        $message = new Message();
        $message->message_text = $request->get('message_text');
        $message->user_id = Auth::user()->id;
        $message->post_id = $post->id;
        $post->messages()->save($message);

        $new_message = Message::where('id', $message->id)->first();

        return response($new_message, 201);

    }

    /**
     * メッセージの削除
     * @params Message $message
     * @return Message
     */
    public function delete(Message $message)
    {
        if ((int) $message->user_id !== Auth::user()->id || ! $message) {
            abort(401);
        } else {
            $message->delete();
        }
    }

    /**
     * メッセージ一覧の取得
     * @params Post $post
     */
    public function index(Post $post)
    {
        $message = Message::where('post_id', $post->id)->get();

        if ($message) {
            return $message;
        } else {
            abort(404);
        }
    }
}
