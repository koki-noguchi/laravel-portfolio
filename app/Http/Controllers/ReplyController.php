<?php

namespace App\Http\Controllers;

use App\Post;
use App\Message;
use App\Http\Requests\StoreReplies;
use App\Reply;
use App\Services\AuthorizationInterface;
use App\Services\ReplyInterface;

class ReplyController extends Controller
{
    /**
     * メッセージ投稿
     * @params Post $post
     * @params Message $message
     * @param StoreReplies $request
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post, Message $message, StoreReplies $request, ReplyInterface $replySave)
    {
        $new_reply = $replySave->Create($request->get('reply_text'), $message);
        return response($new_reply, 201);
    }

    /**
     * メッセージ詳細の取得
     * @params Post $post
     * @params Message $message
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Message $message)
    {
        $message->load([
            'author', 'replies.reply_user.followings', 'replies.reply_user.followers', 'replies.message.post'
        ])->first();
        $message->makeVisible(['replies']);

        return $message ?? abort(404);
    }

    /**
     * メッセージ募集の削除
     * @params Reply $reply
     */
    public function delete(Reply $reply, AuthorizationInterface $authorization)
    {
        $authorization->Check((int) $reply->user_id);
        $reply->delete();
    }
}
