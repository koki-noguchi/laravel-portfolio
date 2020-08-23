<?php

namespace App\Components;

use App\Services\ReplyInterface;
use App\Reply;
use Illuminate\Support\Facades\Auth;

class ReplySave implements ReplyInterface
{
    public function Create($text, $message)
    {
        $reply = new Reply();
        $reply->reply_text = $text;
        $reply->user_id = Auth::user()->id;
        $reply->message_id = $message->id;
        $message->replies()->save($reply);

        $new_reply = Reply::where('id', $reply->id)
            ->with(['reply_user', 'message.post'])->first();

        return $new_reply;
    }
}