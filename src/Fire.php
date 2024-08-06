<?php

namespace src;

class Fire
{
    public string $fpa_id;
    public string $name;
    public string $datetime;
    public string $cause;

    function __construct($fpa_id, $name, $date, $time, $cause)
    {
        $this->fpa_id = $fpa_id;
        $this->name = $name ?? 'No Name Found';
        $this->datetime = $this->getDateTime($date, $time);
        $this->cause = $cause;
    }

    private function getDateTime($date, $time): string
    {
        return $this->julianToGregorian($date) . ' ' . $this->formatTime($time);
    }

    function formatTime($time): string
    {
        return substr($time, 0, 2) . ':' . substr($time, 2, 2);
    }

    private function julianToGregorian($julianDate): string
    {
        $julianDate = intval($julianDate);
        return jdtogregorian($julianDate);
    }
}