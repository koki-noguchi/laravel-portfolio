<?php

namespace App\Components;

use App\Services\MessageInterface;
use App\Message;
use Illuminate\Support\Facades\Auth;

class MessageSave implements MessageInterface
{
    public function LimitCheck($post)
    {
        if ($post->messages->count() >= $post->max_number) {
            abort(401);
            return;
        }
    }

    public function Create($text, $post)
    {
        $message = new Message();
        $message->post_id = $post->id;
        $message->user_id = Auth::user()->id;
        $message->message_text = $text;
        $post->messages()->save($message);

        $new_message = Message::where('id', $message->id)->first();
        return $new_message;
    }
}