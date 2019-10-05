<?php
class Util
{
    static public function getDateString($format, $time, $time_format = null)
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