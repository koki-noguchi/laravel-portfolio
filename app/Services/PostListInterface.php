<?php

namespace App\Services;

interface PostListInterface
{
    public function Search($keyword);
    public function List($user_id);
    public function BookmarkList($post_id);
}