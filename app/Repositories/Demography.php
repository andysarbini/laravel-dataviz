<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Demography 
{
    public function byAge()
    {
        $data = DB::select("
        SELECT
            age_range,
            COUNT(id) count
        FROM (
            SELECT
                *,
            CASE
                WHEN guest_age < 20 THEN '<20'
                WHEN guest_age BETWEEN 20 and 29 THEN '20-29'
                WHEN guest_age BETWEEN 30 and 39 THEN '30-39'
                WHEN guest_age BETWEEN 40 and 49 THEN '40-49'
                WHEN guest_age BETWEEN 50 and 59 THEN '50-59'
                WHEN guest_age >= 60 THEN '> 60'
                WHEN guest_age IS NULL THEN 'Not Filled In (NULL)'
            END as age_range
            FROM bookings
        ) bookings
        GROUP BY age_range
        ORDER BY age_range
        ");

        $data = collect($data ? $data :[]);

        return $data;
    }

    public function byMonthByAge(string $age_range = "", int $year = NULL)
    {
        $data = DB::select("
        SELECT
            YEAR(start_date) year,
            MONTH(start_date) month,
            age_range,
            COUNT(id) count
                FROM (
                    SELECT
                        *,
                        CASE
                            WHEN guest_age < 20 THEN '<20'
                            WHEN guest_age BETWEEN 20 and 29 THEN '20 - 29'
                            WHEN guest_age BETWEEN 30 and 39 THEN '30 - 39'
                            WHEN guest_age BETWEEN 40 and 49 THEN '40 - 49'
                            WHEN guest_age BETWEEN 50 and 59 THEN '50 - 59'
                            WHEN guest_age >= 60 THEN '> 60'
                            WHEN guest_age IS NULL THEN 'Not Filled In (NULL)'
                        END as age_range
                    FROM bookings
                ) bookings
            WHERE age_range = '$age_range' AND YEAR(start_date) = $year
            GROUP BY year, month, age_range
            ORDER BY year, month, age_range
        ");

        $data = collect($data ? $data : []);

        return $data;
    }

    public function byQuarterByAge(string $age_range = '', int $year = NULL)
    {
        $data = DB::select("
        SELECT
            YEAR(start_date) year,
            QUARTER(start_date) quarter,
            age_range,
            COUNT(id) count
        FROM (
            SELECT
                *,
                CASE
                WHEN guest_age < 20 THEN '<20'
                WHEN guest_age BETWEEN 20 and 29 THEN '20 - 29'
                WHEN guest_age BETWEEN 30 and 39 THEN '30 - 39'
                WHEN guest_age BETWEEN 40 and 49 THEN '40 - 49'
                WHEN guest_age BETWEEN 50 and 59 THEN '50 - 59'
                WHEN guest_age >= 60 THEN '> 60'
                WHEN guest_age IS NULL THEN 'Not Filled In (NULL)'
            END as age_range
        FROM bookings
    ) bookings
    WHERE age_range = '$age_range' AND YEAR(start_date) = $year
            GROUP BY year, quarter, age_range
            ORDER BY year, quarter, age_range
        ");
        $data = collect($data ? $data : []);

        return $data;
    }
    
    public function byYearByAge(string $age_range = '', int $start_year = NULL, int $end_year = NULL)
    {
        $data = DB::select("
        SELECT
            YEAR(start_date) year,
            age_range,
            COUNT(id) count
        FROM ( 
            SELECT
                *,
                CASE
                    WHEN guest_age < 20 THEN '<20'
                    WHEN guest_age BETWEEN 20 and 29 THEN '20 - 29'
                    WHEN guest_age BETWEEN 30 and 39 THEN '30 - 39'
                    WHEN guest_age BETWEEN 40 and 49 THEN '40 - 49'
                    WHEN guest_age BETWEEN 50 and 59 THEN '50 - 59'
                    WHEN guest_age >= 60 THEN '> 60'
                    WHEN guest_age IS NULL THEN 'Not Filled In (NULL)'
                END as age_range
            FROM bookings
        ) bookings
        WHERE age_range = '$age_range' AND YEAR(start_date) BETWEEN $start_year AND $end_year
                GROUP BY year, age_range
                ORDER BY year, age_range
        ");
    
        $data = collect($data ? $data : []);
    
        return $data;
    }

    public function byGuestType()
    {
        $data = DB::select("
            SELECT
                guest_type,
                COUNT(id) count
            FROM bookings
            GROUP BY bookings.guest_type
        ");

        $data = collect($data ? $data : []);

        return $data;
    }
}
