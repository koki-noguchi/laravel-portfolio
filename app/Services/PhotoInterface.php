<?php

namespace App\Services;

interface PhotoInterface
{
    public function Create($image);
    public function Delete($image);
}