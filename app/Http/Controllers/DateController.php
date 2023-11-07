<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class DateController extends Controller
{
    public static function formatDatesFromObjects($objects, $date_identifiers, $format)
    {
        if (!is_array($date_identifiers))
        {
            $date_identifiers = [$date_identifiers];
        }

        foreach ($objects as $item)
        {
            foreach ($date_identifiers as $date_identifier)
            {
                if (empty($item->$date_identifier))
                {
                    continue;
                }

                $formatted_date = self::formatDate($item->$date_identifier, $format);
                if (!empty($formatted_date))
                {
                    $item->$date_identifier = $formatted_date;
                }
            }
        }

        return $objects;
    }

    public static function formatDate($date, $format)
    {
        try {
            return Carbon::parse($date)->format($format);
        } catch (\Exception $e) {
            return false;
        }
    }
}
