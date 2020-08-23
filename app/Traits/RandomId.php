<?php namespace App\Traits;

trait RandomId
{
    public function getRandomIdAttribute()
    {
        $id_length = 12;

        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
            );

            $length = count($characters);

            $id = "";

            for ($i = 0; $i < $id_length; $i++) {
                $id .= $characters[random_int(0, $length - 1)];
            }

            return $id;
    }
}