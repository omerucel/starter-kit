<?php

namespace Application\Utility;

class StringOperation
{
    public static function turkishStrToLower($value)
    {
        $chars = array('Ç' => 'ç', 'Ğ' => 'ğ', 'İ' => 'i', 'I' => 'ı','Ö' => 'ö', 'Ş' => 'ş', 'Ü' => 'ü');
        foreach ($chars as $key => $char) {
            $value = str_replace($key, $char, $value);
        }
        return mb_strtolower($value, 'UTF-8');
    }

    public static function turkishUcWords($value)
    {
        $chars = array('ç' => 'Ç', 'ğ' => 'Ğ', 'i' => 'İ', 'ı' => 'I','ö' => 'Ö', 'ş' => 'Ş', 'ü' => 'Ü');
        $output = '';
        $words = explode(' ', $value);
        foreach ($words as $word) {
            $char = mb_substr($word, 0, 1, 'UTF-8');
            if (isset($chars[$char])) {
                $output .= $chars[$char] . mb_substr($word, 1, null, 'UTF-8') . ' ';
            } else {
                $output .= ucfirst($word) . ' ';
            }
        }

        return trim($output);
    }

    public static function createKeywords($value)
    {
        $value = static::turkishStrToLower($value);
        $value = preg_replace('#[^a-zöçşğüı\s]#', ' ', $value);
        $words = explode(' ', $value);

        $temp = [];
        foreach ($words as $word) {
            if (mb_strlen($word, 'UTF-8') > 3) {
                $temp[] = $word;
            }
        }

        return $temp;
    }
}
