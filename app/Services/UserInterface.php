<?php

namespace App\Services;

interface UserInterface
{
    public function Update($user, $params);
    public function EditImage($user);
    public function InitializeImage($user);
}