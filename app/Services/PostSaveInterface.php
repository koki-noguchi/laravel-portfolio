<?php

namespace App\Services;

use PhpParser\Builder\Interface_;

Interface PostSaveInterface
{
    public function Create($title, $password, $max_number, $about);
    public function Update($post, $params);
}