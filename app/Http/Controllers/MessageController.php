<?php

namespace App\Http\Controllers;

use App\Post;
use App\Message;
use App\Http\Requests\StoreMessage;
use App\Services\AuthorizationInterface;
use App\Services\MessageInterface;

class MessageController extends Controller
{
    /**
     * メッセージ投稿
     * @param Post $post
     * @param StoreMessage $request
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post, StoreMessage $request, MessageInterface $messageSave)
    {
        $messageSave->LimitCheck($post);
        $new_message = $messageSave->Create($request->get('message_text'), $post);

        return response($new_message, 201);
    }

    /**
     * メッセージの削除
     * @params Message $message
     * @return Message
     */
    public function delete(Message $message, AuthorizationInterface $authorization)
    {
        $authorization->Check((int) $message->user_id);
        $message->delete();
    }

    /**
     * メッセージ一覧の取得
     * @params Post $post
     */
    public function index(Post $post)
    {
        $message = Message::where('post_id', $post->id)->get();
        return $message;
    }
}
