<?php

namespace App\Services;

interface ReplyInterface
{
    public function Create($text, $message);
}