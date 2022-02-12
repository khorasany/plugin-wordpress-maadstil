<?php

function div($a, $b)
{
    return (int)($a / $b);
}

function readyDateForConvert($date)
{
    $res = explode('/', $date);
    return $res;
}

function christianToShamsi($gy, $gm, $gd, $mod = '')
{
    $g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
    if ($gy > 1600) {
        $jy = 979;
        $gy -= 1600;
    } else {
        $jy = 0;
        $gy -= 621;
    }
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
    $jy += 33 * ((int)($days / 12053));
    $days %= 12053;
    $jy += 4 * ((int)($days / 1461));
    $days %= 1461;
    if ($days > 365) {
        $jy += (int)(($days - 1) / 365);
        $days = ($days - 1) % 365;
    }
    $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
    $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
    return ($mod == '') ? array($jy, $jm, $jd) : $jy . $mod . $jm . $mod . $jd;
}

function timeStampToShamsiWithoutTime($timeStamp, $mode = null)
{
    $fundDate = date('Y/m/d H:i:s', $timeStamp);
    $fundDate = str_split($fundDate, 11);
    $rtrim = rtrim($fundDate[0], ' ');
    $rtrim = str_replace('/', '', $rtrim);
    $dateYear = str_split($rtrim, 4);
    $dateMonDay = str_split($dateYear[1], 2);
    $dateConvertedFund = christianToShamsi($dateYear[0], $dateMonDay[0], $dateMonDay[1]);
    $shamsiFund = $dateConvertedFund[2] . '/' . $dateConvertedFund[1] . '/' . $dateConvertedFund[0];
    if($mode != null) $shamsiFund = $dateConvertedFund[0] . '/' . $dateConvertedFund[1] . '/' . $dateConvertedFund[2];
    return $shamsiFund;
}

function shamsiToChristian($j_y, $j_m, $j_d, $str)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);


    $jy = (int)($j_y) - 979;
    $jm = (int)($j_m) - 1;
    $jd = (int)($j_d) - 1;

    $j_day_no = 365 * $jy + div($jy, 33) * 8 + div($jy % 33 + 3, 4);

    for ($i = 0; $i < $jm; ++$i)
        $j_day_no += $j_days_in_month[$i];

    $j_day_no += $jd;

    $g_day_no = $j_day_no + 79;

    $gy = 1600 + 400 * div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
    $g_day_no = $g_day_no % 146097;

    $leap = true;
    if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ {
        $g_day_no--;
        $gy += 100 * div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
        $g_day_no = $g_day_no % 36524;

        if ($g_day_no >= 365)
            $g_day_no++;
        else
            $leap = false;
    }

    $gy += 4 * div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
    $g_day_no %= 1461;

    if ($g_day_no >= 366) {
        $leap = false;

        $g_day_no--;
        $gy += div($g_day_no, 365);
        $g_day_no = $g_day_no % 365;
    }

    for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
        $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
    $gm = $i + 1;
    $gd = $g_day_no + 1;
    if ($str) return $gy . '-' . $gm . '-' . $gd;
    return array($gy, $gm, $gd);
}