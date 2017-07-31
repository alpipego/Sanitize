<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 31.07.2017
 * Time: 12:29
 */
declare(strict_types = 1);

namespace WPHibou\Sanitize;


function encoding(string $string) : string
{
    return htmlspecialchars_decode(utf8_decode(htmlentities($string, ENT_COMPAT, 'utf-8', false)));
}

function permalink(string $string) : string
{
    // remove any whitespace
    $string = preg_replace('%\h%', '-', mb_strtolower((string)$string));
    // convert German Umlauts and SZ
    $string = str_replace(['ä', 'ü', 'ö', 'ß'], ['ae', 'ue', 'oe', 'ss'], $string);
    // replace anything that is not a-z or digit
    $string = preg_replace('%[^a-z0-9\-]%', '', $string);
    // replace multiple dashes with one
    $string = preg_replace('%-+%', '-', $string);

    return $string;
}

function numberFormat($number, int $decPlaces = 2, string $decPoint = ',', string $thSeperator = '&#8239;') : string
{
    $dcs  = explode('.', floatval($number));
    $left = $dcs[0];
    if (strlen($left) > 4) {
        $left = str_split(strrev($left), 3);
        $left = strrev(implode($thSeperator, $left));
    }

    return $left . $decPoint . str_pad($dcs[1], $decPlaces, '0');
}
