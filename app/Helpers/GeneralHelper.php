<?php

namespace App\Helpers;

class GeneralHelper
{
    /**
     * @param string $start
     * @param string $end
     * @return string
     * @throws Exception
     */
    public static function ReportDateRange($start, $end)
    {
        return self::DateFormatter($start, 'M d - ') . self::DateFormatter($end, 'd, Y');
    }

    /**
     * @param string $date
     * @param string $format
     * @return string
     * @throws Exception
     */
    public static function DateFormatter($date, $format = 'm/d/Y')
    {
        $date = new \DateTime($date);
        return $date->format($format);
    }
}