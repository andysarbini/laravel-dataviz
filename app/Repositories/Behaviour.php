<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class Behaviour {

    public function rooms(int $year = NULL)
    {
        $data = DB::table('bookings')
            ->select([DB::raw('COUNT(id) count'), 'room_category'])
            ->groupBy('room_category')
            ->orderBy('room_category');

        if($year)
        {
            $data = $data->whereYear('start_date', '=', $year);
        }

        return $data->get();
    }

    public function roomsByGuestOrigin(string $category = '', int $year = NULL)
    {
        $data = db::table('bookings')
            ->select(['room_category', 'guest_origin', DB::raw('COUNT(id) count')])
            ->where('room_category', '=', $category)
            ->groupBy('room_category')
            ->groupBy('guest_origin')
            ->orderBy('room_category')
            ->orderBy('guest_origin');

        if($year)
        {
            $data = $data->whereYear('start_date', '=', $year);
        }
        return $data->get();
    }

    public function roomsByAgeRange(string $category = '', int $year = NULL)
    {
        $derived = DB::table('bookings')
            ->select([
                "id",
                "room_category",
                "guest_age",
                "start_date",
                DB::raw("CASE
                    WHEN guest_age < 20 THEN '< 20'
                    WHEN guest_age BETWEEN 20 and 29 THEN '20 - 29'
                    WHEN guest_age BETWEEN 30 and 39 THEN '30 - 39'
                    WHEN guest_age BETWEEN 40 and 49 THEN '40 - 49'
                    WHEN guest_age BETWEEN 50 and 59 THEN '50 - 59'
                    WHEN guest_age >= 60 THEN '> 60'
                    WHEN guest_age IS NULL THEN 'Not Filled In (NULL)'
                    END as age_range
                    ")
            ]);

        $data = Booking::select([
            'age_range',
            'room_category',
            DB::raw('COUNT(id) count')
        ])
            ->from(DB::raw("({$derived->toSQL()}) bookings"))
            ->where('room_category', '=', $category)
            ->groupBy('room_category')
            ->groupBy('age_range')
            ->orderBy('room_category')
            ->orderBy('age_range');

        if($year !== NULL)
        {
            $data = $data->whereYear('start_date', '=', $year);
        }

        return $data->get();
    }

    public function roomsByGuestType(string $category = '', int $year = NULL)
    {
        $data = DB::table('bookings')
            ->select(['room_category', 'guest_type', DB::raw('COUNT(id) count')])
            ->where('room_category', '=', $category)
            ->groupBy('room_category')
            ->groupBy('guest_type')
            ->orderBy('room_category')
            ->orderBy('guest_type');

        if($year)
        {
            $data = $data->whereYear('start_date', '=', $year);
        }

        return $data->get();
    }

    public function getAgeRanges(int $year = NULL)    
    {
        $age_ranges = DB::table('bookings')
            ->distinct()
            ->orderBy('age_range');

        if($year)
        {
            $age_ranges = $age_ranges->whereRaw("YEAR(start_date) = ?", [$year]);
        }

        $age_ranges = $age_ranges->get(DB::raw("
            CASE
                WHEN guest_age < 20 THEN '< 20'
                WHEN guest_age BETWEEN 20 and 29 THEN '20 - 29'
                WHEN guest_age BETWEEN 30 and 39 THEN '30 - 39'
                WHEN guest_age BETWEEN 40 and 49 THEN '40 - 49'
                WHEN guest_age BETWEEN 50 and 59 THEN '50 - 59'
                WHEN guest_age >= 60 THEN '> 60'
                WHEN guest_age IS NULL THEN 'Not Filled In (NULL)'
            END as age_range
        "))
        ->pluck('age_range')
        ->toArray();

        return $age_ranges;
    }

    public function duration(int $year = NULL)
    {
        $data = DB::table('bookings')
            ->select(['duration', DB::raw('COUNT(id) count')])
            ->groupBy('duration')
            ->orderBy('duration');

        if($year)
        {
            $data = $data->whereYear('start_date', '=', $year);
        }

        return $data->get();
    }

    public function durationByOrigin(string $duration, int $year = NULL)
    {
        $data = DB::table('bookings')
            ->select(['duration', 'guest_origin', DB::raw('COUNT(id) count')])
            ->where('duration', '=', $duration)
            ->groupBy('duration')
            ->groupBy('guest_origin')
            ->orderBy('duration')
            ->orderBy('guest_origin');

        if($year)
        {
            $data = $data->whereYear('start_date', '=', $year);
        }

        return $data->get();
    }

    public function durationByAgeRange(string $duration, int $year = NULL)
    {
        $derived = DB::table('bookings')
            ->select([
                "id",
                "duration",
                "guest_age",
                "start_date",
                DB::raw("CASE
                    WHEN guest_age < 20 THEN '< 20'
                    WHEN guest_age BETWEEN 20 and 29 THEN '20 - 29'    
                    WHEN guest_age BETWEEN 30 and 39 THEN '30 - 39'    
                    WHEN guest_age BETWEEN 40 and 49 THEN '40 - 49'    
                    WHEN guest_age BETWEEN 50 and 59 THEN '50 - 59'  
                    WHEN guest_age >= 60 THEN '> 60'
                    WHEN guest_age IS NULL THEN 'Not Filled In (NULL)'
                    END as age_range  
                ")
            ]);

            $data = Booking::select([
                    'age_range',
                    'duration',
                    DB::raw('COUNT(id) count')
            ])
            ->from(DB::raw("({$derived->toSql()}) bookings"))
            ->where('duration', '=', $duration)
            ->groupBy('duration')
            ->groupBy('age_range')
            ->orderBy('duration')
            ->orderBy('age_range');

            if($year !== NULL){
                $data = $data->whereYear('start_date', '=', $year);
            }

            return $data->get();
    }

    public function durationByGuestType(string $duration, int $year = NULL)
    {
        $data = DB::table('bookings')
            ->select(['duration', 'guest_type', DB::raw('COUNT(id) count')])
            ->where('duration', '=', $duration)
            ->groupBy('duration')
            ->groupBy('guest_type')
            ->orderBy('duration')
            ->orderBy('guest_type');

        if($year){
            $data = $data->whereYear('start_date', '=', $year);
        }

        return $data->get();
    }

}