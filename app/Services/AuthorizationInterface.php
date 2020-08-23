<?php

namespace App\Services;

interface AuthorizationInterface
{
    public function Check($id);
    public function CheckGuest();
}