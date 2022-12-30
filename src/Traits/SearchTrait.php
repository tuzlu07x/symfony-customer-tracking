<?php

namespace App\Traits;

trait SearchTrait
{
    public function splitName($fullName)
    {
        $countWord = strlen($fullName);
        $array = [];
        for ($i = 0; $i < $countWord; $i++) {
            if ($fullName[$i]) {
                $firstName = substr($fullName, 0, $i);
                $lastName = substr($fullName, $i);
                $array[] = [$firstName, $lastName];
            }
        }
        return $array;
    }
}
