<?php

namespace reward\components;

use DateInterval;
use DatePeriod;
use DateTime;

class Helpers
{

    public static function getNextBandList()
    {
        return [
            '1.a' => '1.b',
            '1.b' => '1.c',
            '1.c' => '1.d',
            '1.d' => '1.e',
            '1.e' => '1.f',
            '1.f' => '1.g',
            '1.g' => '1.h',
            '1.h' => '1.i',
            '1.i' => '1.j',
            '1.j' => '2.1',
            '2.1' => '2.2',
            '2.2' => '2.3',
            '2.3' => '2.4',
            '2.4' => '2.5',
            '2.5' => '3.1',
            '3.1' => '3.2',
            '3.2' => '3.3',
            '3.3' => '4.1',
            '4.1' => '4.2',
            '4.2' => '4.3',
            '4.3' => '5.1',
            '5.1' => '5.2',
            '5.2' => '5.3',
            '5.3' => '6.1',
            '6.1' => '6.2',
            '6.2' => '6.3',
        ];
    }

    public static function nextBand($bi)
    {
        $list = Helpers::getNextBandList();
        if (array_key_exists($bi, $list)) {
            return $list[$bi];
        } else {
            return NULL;
        }
    }


    public static function getBiList()
    {
        $data = [
            '1.a' => '1.a',
            '1.b' => '1.b',
            '1.c' => '1.c',
            '1.d' => '1.d',
            '1.e' => '1.e',
            '1.f' => '1.f',
            '1.g' => '1.g',
            '1.h' => '1.h',
            '1.i' => '1.i',
            '1.j' => '1.j',
            '2.1' => '2.1',
            '2.2' => '2.2',
            '2.3' => '2.3',
            '2.4' => '2.4',
            '2.5' => '2.5',
            '3.1' => '3.1',
            '3.2' => '3.2',
            '3.3' => '3.3',
            '4.1' => '4.1',
            '4.2' => '4.2',
            '4.3' => '4.3',
            '5.1' => '5.1',
            '5.2' => '5.2',
            '5.3' => '5.3',
            '6.1' => '6.1',
            '6.2' => '6.2',
            '6.3' => '6.3',
        ];

        return $data;
    }

    public static function getBpList()
    {
        $data = [
            '1'   => '1',
            '2.1' => '2.1',
            '2.2' => '2.2',
            '2.3' => '2.3',
            '2.4' => '2.4',
            '2.5' => '2.5',
            '3.1' => '3.1',
            '3.2' => '3.2',
            '3.3' => '3.3',
            '4.1' => '4.1',
            '4.2' => '4.2',
            '4.3' => '4.3',
            '5.1' => '5.1',
            '5.2' => '5.2',
            '5.3' => '5.3',
            '6.1' => '6.1',
            '6.2' => '6.2',
            '6.3' => '6.3',
        ];

        return $data;
    }

    public static function getBandList()
    {
        $data = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        ];

        return $data;
    }

    public static function getEmpCategoryList()
    {
        return  [
            'CONTRACT'      => 'CONTRACT',
            'CONTRACT PROF' => 'CONTRACT PROF',
            'EXPATRIATE'    => 'EXPATRIATE',
            'PERMANENT'     => 'PERMANENT',
            'PROBATION'     => 'PROBATION',
            'SINGTEL'       => 'SINGTEL',
            'TELKOM'        => 'TELKOM',
            'TRAINEE'       => 'TRAINEE'
        ];
    }

    public static function getCriteria()
    {
        $data = [
            'emp_category'  => 'Employee Category',
            'band_individu' => 'Band Individu',
            'band_position' => 'Band Position',
            'structural'    => 'Structural',
            'functional'    => 'Functional',
            'marital_status'=> 'Marital Status',
            'gender'        => 'Gender',
            'organization'  => 'Organization',
            'job'           => 'Job',
            'location'      => 'Location',
            'kota'          => 'Kota',
            'department'    => 'Department',
            'division'      => 'Division',
            'homebase'      => 'Homebase',
            'band'          => 'Band',
        ];

        return $data;
    }

    public static function getType()
    {
        $data = [
            'general_increase'  => 'General Increase',
        ];

        return $data;
    }

    /**
     * @param $date1 string Start date
     * @param $date2 string End date
     * @return DatePeriod
     */
    public static function getMonthIterator($date1, $date2)
    {
        $begin = new DateTime( $date1 );
        $end = new DateTime( $date2 );
//        $end->modify('+1 month');

        $interval = DateInterval::createFromDateString('1 month');

        $period = new DatePeriod($begin, $interval, $end);

        return $period;
    }

    /**
     * @param $month int The Month
     * @param $year int The Year
     * @return int
     */
    public static function getDaysInMonth($month, $year)
    {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public static function getTahun()
    {
        $currentYear = date('Y');
        $endYear = $currentYear + 5;

        $yearArray = range($currentYear, $endYear);

        return $yearArray;
    }

    public static function truncateText($text, $max_len)

    {

        $len = strlen($text);

        if ($len <= $max_len)

            return $text;

        else

            return substr($text, 0, $max_len - 1) . '...';

    }
}

?>