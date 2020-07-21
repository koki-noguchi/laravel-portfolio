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
    public function addMessage(Post $post, StoreMessage $request)
    {
        $message = new Message();
        $message->message_text = $request->get('message_text');
        $message->user_id = Auth::user()->id;
        $message->post_id = $post->id;
        $post->messages()->save($message);

        $new_message = Message::where('id', $message->id)->with('author')->first();

        return response($new_message, 201);

    }

    /**
     * メッセージ募集の削除
     * @params string $id
     * @return Message
     */
    public function delete(string $id)
    {
        $message = Message::where('id', $id)->first();

        if ((int) $message->user_id !== Auth::user()->id || ! $message) {
            abort(401);
        } else {
            $message->delete();
        }
    }
}
