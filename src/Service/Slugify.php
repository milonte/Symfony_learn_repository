<?php

namespace App\Service;

class Slugify
{
    const CHAR_REPLACE = [
        " " => "-",
        "," => "-",
        "'" => "-",
        "!" => "-",
        "à" => "a",
        "ç" => "c"
    ];

    public function generate(string $input) : string
    {
        $input = trim($input);
        foreach(self::CHAR_REPLACE as $key => $value)
        {
            $input = str_replace($key, $value, $input);
        }
        return $input;
    }
}
