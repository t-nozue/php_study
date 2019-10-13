<?php
class Util
{
    /**
     * getDateString function
     * 
     * @param string $format
     * @param int $tiem
     * @param string $tiem_format
     * @return string
     */
    public static function getDateString($format, $time, $time_format = null)
    {
        $date_time_zone = new DateTimeZone(TIME_ZONE);
        if (empty($time_format)) {
            $date_time = new DateTime($time, $date_time_zone);
        } else {
            $date_time = DateTime::createFromFormat($time_format, $time, $date_time_zone);
        }
        return $date_time->format($format);
    }
}
