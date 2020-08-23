<?php

namespace App\Services;

interface AuthorizationInterface
{
    public function Check($id);
}