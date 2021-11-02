<?php

namespace Util;

class Util
{
    /**
     * Initialize credentials with default values
     */
    public function __construct()
    {

    }

    /**
     * This will accept a string and convert and return it as a slug by removing
     * special characters and replacing spaces with dashes. It will also lowercase
     * the string.
     */
    public function stringToSlug($str)
    {
        // Turn the string into a lowercase
        $str = strtolower(trim($str));

        // Perform slugging operation on accepted string
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        // Remove the hypen from start
        $str = ltrim($str, '-');

        // Remove hyphen from tail
        $str = rtrim($str, '-');
        return $str;
    }
}
