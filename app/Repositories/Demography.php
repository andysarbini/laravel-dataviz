<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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

        $allCount = $data->sum('count');

        $addPercentage = function($item) use($allCount) {
            if($allCount == 0) {
                $item->percentage = 0;
            } else {
                $item->percentage = $item->count / $allCount * 100;
                $item->percentage = number_format((float)$item->percentage, "2");
            }
            return $item;
        };

        $data = $data->map($addPercentage);

        return $data;
    }

    public function byMonthByAge(string $age_range = "", int $year = NULL)
    {
        IF(NULL == $year) $year = date("Y");

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

        // return $data;
        return $this->fillMonth($data, $year);
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
    
        // return $data;
        return $this->fillYear($data, $start_year, $end_year);

  
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

        $allCount = $data->sum('count');

        $addPercentage = function($item) use($allCount) {
            if($allCount == 0) {
                $item->percentage = 0;
            } else {
                $item->percentage = $item->count / $allCount * 100;
                $item->percentage = number_format((float)$item->percentage, "2");
            }
            return $item;
        };

        $data = $data->map($addPercentage);

        return $data;
    }

    public function byMonthByGuestType(string $type = '', int $year = NULL)
    {

        IF(NULL == $year) $year = date("Y");

        $data = DB::select("
        SELECT
            YEAR(start_date) year,
            MONTH(start_date) month,
            guest_type,
            COUNT(id) count
        FROM bookings
        WHERE guest_type = '$type' AND YEAR(start_date) = $year
        GROUP BY year, month, guest_type
        ORDER BY year, month, guest_type
        ");

        $data = collect($data ? $data : []);

        // return $data;
        return $this->fillMonth($data, $year);
    }
    
    public function byQuarterByGuestType(string $type = '', int $year = NULL)
    {
        $data = DB::select("
        SELECT
            YEAR(start_date) year,
            QUARTER(start_date) quarter,
            guest_type,
            COUNT(id) count
        FROM bookings
        WHERE guest_type = '$type' AND YEAR(start_date) = $year
        GROUP BY year, quarter, guest_type
        ORDER BY year, quarter, guest_type
        ");

        $data = collect($data ? $data : []);

        return $data;
    }

    public function byYearByGuestType(string $type= '', int $start_year = NULL, int $end_year = NULL)
    {
        $data = DB::select("
        SELECT
            guest_type,
            YEAR(start_date) year,
            COUNT(id) count
        FROM bookings
        WHERE guest_type = '$type' AND YEAR(start_date) BETWEEN $start_year AND $end_year
        GROUP BY year, guest_type
        ORDER BY year, guest_type
        ");

        $data = collect($data ? $data : []);

        // return $data;
        return $this->fillYear($data, $start_year, $end_year);

  
    }

    protected function fillYear(collection $data, $start_year, $end_year)
    {
        $result = collect(array_fill(0, $end_year - $start_year + 1, $start_year)) 
            ->map(function($value, $index) use($data){
                $current_year = $value + $index;

                $byYear = function($item) use ($current_year){
                    return $item->year == $current_year;
                };

                return $data->pluck("year")->contains($current_year)
                    ? $data->filter($byYear)->first()
                    : [
                        "year" => $current_year,
                        "count" => 0,
                    ];
            });

            return $result;
    }

    protected function fillMonth(Collection $data, $year)
    {
        $result = collect(array_fill(0, 12, NULL))->map(function($_, $index) use ($data, $year) {
            $byMonth = function($item) use ($index) {
                return $item->month == $index + 1;
            };

            return $data->pluck('month')->contains($index + 1)
                ? $data->filter($byMonth)->first()
                : ["month" => $index + 1, "count" => 0, "year" => $year];
        });

        return $result;
    }

    public function byOrigin()
    {
        $data = DB::select("
            SELECT
                guest_origin,
                COUNT(id) count
            FROM bookings
            GROUP BY guest_origin
            ORDER BY guest_origin
        ");

        $data = collect($data ? $data : []);

        return $data;
    }

    public function byMonthByOrigin(string $origin = "", int $year = NULL)
    {
        IF(NULL == $year) $year = date("Y");

        $data = DB::select("
            SELECT
                YEAR(start_date) year,
                MONTH(start_date) month,
                guest_origin,
                COUNT(id) count
            FROM bookings
            WHERE guest_origin = '$origin' AND YEAR(start_date) = $year
            GROUP BY year, month, guest_origin
            ORDER BY year, month, guest_origin
        ");

        $data = collect($data ? $data : []);

        // return $data;
        return $this->fillMonth($data, $year);
    }

    public function byQuarterByOrigin(string $origin = '', int $year = NULL)
    {
        $data = DB::select("
        SELECT
            YEAR(start_date) year,
            QUARTER(start_date) quarter,
            guest_origin,
            COUNT(id) count
        FROM bookings
        WHERE guest_origin = '$origin' AND YEAR(start_date) = $year
        GROUP BY year, quarter, guest_origin
        ORDER BY year, quarter, guest_origin
        ");

        $data = collect($data ? $data : []);

        return $data;
    }

    public function byYearByOrigin(string $origin= '', int $start_year = NULL, int $end_year = NULL)
    {
        $data = DB::select("
        SELECT
            YEAR(start_date) year,
            guest_origin,
            COUNT(id) count
        FROM bookings
        WHERE guest_origin = '$origin' AND YEAR(start_date) BETWEEN $start_year AND $end_year
        GROUP BY year, guest_origin
        ORDER BY year, guest_origin
        ");

        $data = collect($data ? $data : []);

        // return $data;
        return $this->fillYear($data, $start_year, $end_year);
    }

}
