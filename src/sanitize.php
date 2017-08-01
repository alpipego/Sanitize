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
    if ($decPoint === ',') {
        if (mb_strpos($number, ',')) {
            $number = str_replace('.', '', $number);
        }
        $number = str_replace(',', '.', $number);
    }

    $number = (string)floatval($number);
    $dcs    = explode('.', $number);
    if (mb_strlen($dcs[1] ?? '') > $decPlaces) {
        $round = str_split($dcs[1], $decPlaces);
        $dcs[1] = (string)((int)mb_substr($round[1], 0, 1) > 4 ? (int)$round[0] + 1 : $round[0]);
    } else {
        $dcs[1] = str_pad($dcs[1] ?? '', $decPlaces, '0');
    }
    $minus = '';
    if (mb_strpos($dcs[0], '-') === 0) {
        $minus  = '-';
        $dcs[0] = ltrim($dcs[0], '-');
    }
    foreach ($dcs as $key => &$group) {
        if (mb_strlen($group) > 4) {
            $dummySeperator = '$';
            $group          = str_split($key ? $group : strrev($group), 3);
            $group          = implode($dummySeperator, $group);
            if (! $key) {
                $group = strrev($group);
            }
            $group = str_replace($dummySeperator, $thSeperator, $group);
        }
    }
    if (! isset($dcs[1])) {
        $dcs[1] = '0';
    }

    return $minus . $dcs[0] . $decPoint . $dcs[1];
}
