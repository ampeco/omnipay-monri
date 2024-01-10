<?php

namespace Ampeco\OmnipayMonri;

trait ManipulatesString
{
    public function trimText(string $text, int $maxLength = 100)
    {
        return mb_strimwidth($text, 0, $maxLength, '...');
    }

    public function isJson($string)
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
