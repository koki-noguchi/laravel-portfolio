<?php

namespace App\Services;

interface MessageInterface
{
    public function LimitCheck($post);
    public function Create($text, $post);
}