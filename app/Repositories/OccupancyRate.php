<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OccupancyRate {

    // (1) terima parameter $year
    public function byMonth($year = NULL)
    {
        // (2) jika year bernilai NULL, kita ambil tahun saat ini
        if(NULL == $year) $year = date("Y");

        $data = DB::select("
        SELECT 
    t.month, 
    SUM(t.duration) / (AVG(t.days_in_month) * (SELECT COUNT(id) FROM rooms WHERE rooms.created_at <= (t.last_day) )) * 100 occupancy_rate,
    t.year
FROM (
    SELECT
        room_id,
        room_category,
        start_date,
        end_date,
        DATEDIFF( LEAST(end_date, LAST_DAY(start_date)), start_date ) duration,
        DAYOFMONTH(LAST_DAY(start_date)) days_in_month,
        LAST_DAY(start_date) last_day,
        MONTH(start_date) month,
        YEAR(start_date) year
    FROM
        bookings
    UNION 
    SELECT
        room_id,
        room_category,
        start_date,
        end_date,
        DATEDIFF(end_date, GREATEST(start_date,DATE_SUB(end_date, INTERVAL DAYOFMONTH(end_date) DAY ) )) duration,
        DAYOFMONTH(LAST_DAY(end_date)) days_in_month,
        LAST_DAY(end_date) last_day,
        MONTH(end_date) month,
        YEAR(end_date) YEAR
    FROM
        bookings 
) t
WHERE t.year = $year 
GROUP BY t.year, t.month, t.last_day
ORDER BY t.year, t.month
        ");

    $data = collect($data ? $data : []);

    return $this->fillMonth($data, $year);
    }

    public function byQuarter($year = NULL)
    {
        if($year == NULL) $year = date("Y");

        $data = DB::select("
        SELECT 
        t.quarter, 
        SUM(t.duration) / (AVG(t.days_in_quarter) * (SELECT COUNT(id) FROM rooms WHERE rooms.created_at <= (t.last_day)  )) * 100 occupancy_rate,
        t.year
        FROM (
            SELECT
            room_id,
            room_category,
            start_date,
            end_date,
            DATEDIFF( LEAST(end_date, MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY ), start_date ) duration,
            DATEDIFF(MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY,  MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 QUARTER) as days_in_quarter,
            MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY last_day,
            QUARTER(start_date) quarter,
            YEAR(start_date) year
        FROM
            bookings
        UNION 
            SELECT
            room_id,
            room_category,
            start_date,
            end_date,
            DATEDIFF( end_date, GREATEST(start_date,  MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 QUARTER - INTERVAL 1 DAY ) ) duration,
            DATEDIFF(MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 DAY, MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 QUARTER ) as days_in_quarter,
            MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 DAY last_day,
            QUARTER(end_date) quarter,
            YEAR(end_date) YEAR
        FROM
            bookings 
        ) t
        WHERE t.year = $year 
        GROUP BY t.year, t.quarter, t.last_day
        ORDER BY t.year, t.quarter
    ");
 
    $data = collect($data ? $data : []);
 
    return $data;
    }

    public function byYear($start_year, $end_year)
    {
        $data = DB::select("
        SELECT
        SUM(t.duration) / (AVG(t.days_in_year) * (SELECT COUNT(id) FROM rooms WHERE YEAR(created_at) <= t.year )) * 100 occupancy_rate,
        t.year
        FROM (
            SELECT
                room_id,
                room_category,
                start_date,
                end_date,
                DATEDIFF( LEAST(end_date, LAST_DAY(DATE_ADD(start_date, INTERVAL 12 - MONTH(start_date) MONTH )) ), start_date ) duration,
                DAYOFYEAR( LAST_DAY(DATE_ADD(start_date, INTERVAL 12 - MONTH(start_date) MONTH))) as days_in_year,
                YEAR(start_date) year
                FROM
                    bookings
                UNION

                SELECT
                    room_id,
                    room_category,
                    start_date,
                    end_date,
                    DATEDIFF( end_date, GREATEST(start_date, MAKEDATE(YEAR(end_date), 1) ) ) duration,
                    DAYOFYEAR(LAST_DAY(DATE_ADD(end_date, INTERVAL 12 - MONTH(end_date) MONTH))) as days_in_year,
                    YEAR(end_date) YEAR
                FROM
                    bookings
                    ) t
                    WHERE t.year BETWEEN $start_year AND $end_year
                    GROUP BY t.year
                    ORDER BY t.year
            ");
            
        $data = collect($data ? $data : []);
        return $this->fillYear($data, $start_year, $end_year);
        }

    public function byMonthByRoom(string $category, int $year = NULL)
    {
        IF(NULL == $year) $year = date("Y");

        $data = DB::select("
            SELECT
                t.month,
                t.room_category,
                SUM(t.duration) / (AVG(t.days_in_month) * (SELECT COUNT(id) FROM rooms WHERE rooms.category = t.room_category AND rooms.created_at <= (t.last_day) )) * 100 occupancy_rate,
                t.year
                FROM (
                    SELECT
                        room_id,
                        room_category,
                        start_date,
                        end_date,
                        DATEDIFF( LEAST(end_date, LAST_DAY(start_date)), start_date ) duration,
                        DAYOFMONTH(LAST_DAY(start_date)) days_in_month,
                        LAST_DAY(start_date) last_day,
                        MONTH(start_date) month,
                        YEAR(start_date) year
                    FROM
                        bookings
                    UNION
                        SELECT
                            room_id,
                            room_category,
                            start_date,
                            end_date,
                            DATEDIFF(end_date, GREATEST(start_date, DATE_SUB(end_date, INTERVAL DAYOFMONTH(end_date) DAY ) )) duration,
                            DAYOFMONTH(LAST_DAY(end_date)) days_in_month,
                            LAST_DAY(start_date) last_day,
                            MONTH(end_date) month,
                            YEAR(end_date) YEAR
                        FROM
                            bookings
                        ) t
                    WHERE t.year = $year AND t.room_category = '$category'
                    GROUP BY t.year, t.month, t.room_category
                    ORDER BY t.year, t.month
                    
        ");

        $data = collect($data ? $data: []);

        return $this->fillMonth($data, $year);
    }

    public function byQuarterByRoom(string $category, int $year = NULL)
    {
        $data = DB::select("
            SELECT
                t.quarter,
                SUM(t.duration) / (AVG(t.days_in_quarter) * (SELECT COUNT(id) FROM rooms WHERE rooms.category = t.room_category AND rooms.created_at <= (t.last_day) )) * 100 occupancy_rate,
                t.room_category,
                t.year
            FROM (
                SELECT
                    room_id,
                    room_category,
                    start_date,
                    end_date,
                    DATEDIFF( LEAST(end_date, MAKEDATE(YEAR(start_date),1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY), start_date ) duration,
                    DATEDIFF(MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY, MAKEDATE(YEAR(start_date),1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 QUARTER) as days_in_quarter,
                    MAKEDATE(YEAR(start_date),1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY last_day,
                    QUARTER(start_date) quarter,
                    YEAR(start_date) year
                FROM
                    bookings
                UNION
                    SELECT
                        room_id,
                        room_category,
                        start_date,
                        end_date,
                        DATEDIFF( end_date, GREATEST(start_date, MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 QUARTER - INTERVAL 1 DAY)) duration,
                        DATEDIFF(MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 DAY, MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 QUARTER) as days_in_quarter,
                        MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 DAY last_day,
                        QUARTER(end_date) quarter,
                        YEAR(end_date) YEAR
                    FROM
                        bookings
                    ) t
                    WHERE t.year = $year AND t.room_category = '$category'
                    GROUP BY t.year, t.quarter, t.room_category
                    ORDER BY t.year, t.quarter
        ");

        $data = collect($data ? $data : []);

        return $data;
    }

    public function byYearByRoom(string $category, int $start_year = NULL, int $end_year = NULL)
    {
        $data = DB::select("
            SELECT
                SUM(t.duration) / (AVG(t.days_in_year) * (SELECT COUNT(id) FROM rooms WHERE rooms.category AND YEAR(created_at) <= t.year )) * 100 occupancy_rate,
                t.room_category,
                t.year
            FROM (
                SELECT
                    room_id,
                    room_category,
                    start_date,
                    end_date,
                    DATEDIFF( LEAST(end_date, LAST_DAY(DATE_ADD(start_date, INTERVAL 12 - MONTH(start_date) MONTH )) ), start_date ) duration,
                    DAYOFYEAR(LAST_DAY(DATE_ADD(start_date, INTERVAL 12 - MONTH(start_date) MONTH))) as days_in_year,
                    YEAR(start_date) year
                FROM
                    bookings
                UNION

                    SELECT
                        room_id,
                        room_category,
                        start_date,
                        end_date,
                        DATEDIFF( end_date, GREATEST(start_date, MAKEDATE(YEAR(end_date), 1) ) ) duration,
                        DAYOFYEAR(LAST_DAY(DATE_ADD(end_date, INTERVAL 12 - MONTH(end_date) MONTH))) as days_in_year, 
                        YEAR(end_date) YEAR
                    FROM
                        bookings
                    ) t
                    WHERE t.room_category = '$category'
                    AND t.year BETWEEN $start_year AND $end_year
                    GROUP BY t.year, t.room_category
                    ORDER BY t.year
        ");

        $data = collect($data ? $data :[]);

        return $this->fillYear($data, $start_year, $end_year, $category);
    }

    public function byMonthByBed(string $bed_type, int $year = NULL)
    {
        $data = DB::select("
            SELECT
                t.month,
                SUM(t.duration) / (AVG(t.days_in_month) * (SELECT COUNT(id) FROM rooms WHERE rooms.bed_type = t.room_bed AND rooms.created_at <= (t.last_day) )) * 100 occupancy_rate,
                t.room_bed,
                t.year
            FROM (
                SELECT
                    room_id,
                    room_bed,
                    start_date,
                    end_date,
                    DATEDIFF( LEAST(end_date, LAST_DAY(start_date)), start_date ) duration,
                    DAYOFMONTH(LAST_DAY(start_date)) days_in_month,
                    LAST_DAY(start_date) last_day,
                    MONTH(start_date) month,
                    YEAR(start_date) year
                FROM
                    bookings
                UNION

                    SELECT
                        room_id,
                        room_bed,
                        start_date,
                        end_date,
                        DATEDIFF(end_date, GREATEST(start_date, DATE_SUB(end_date, INTERVAL DAYOFMONTH(end_date) DAY ) )) duration,
                        DAYOFMONTH(LAST_DAY(end_date)) days_in_month,
                        LAST_DAY(start_date) last_day,
                        MONTH(end_date) month,
                        YEAR(end_date) YEAR
                    FROM
                        bookings
                    ) t
                    WHERE t.year = $year AND t.room_bed = '$bed_type'
                    GROUP BY t.year, t.month, t.room_bed
                    ORDER BY t.year, t.month
        ");

        $data = collect($data ? $data :[]);

        return $this->fillMonth($data, $year);
    }

    public function byQuarterByBed(string $bed_type, int $year = NULL)
    {
        $data = DB::select("
            SELECT
                t.quarter,
                SUM(t.duration) / (AVG(t.days_in_quarter) * (SELECT COUNT(id) FROM rooms WHERE rooms.bed_type = t.room_bed AND rooms.created_at <= (t.last_day) )) * 100 occupancy_rate,
                t.room_bed,
                t.year
            FROM (
                SELECT
                    room_id,
                    room_bed,
                    start_date,
                    end_date,
                    DATEDIFF( LEAST(end_date, MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY), start_date ) duration,
                    DATEDIFF(MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY, MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 QUARTER) as days_in_quarter, 
                    MAKEDATE(YEAR(start_date), 1) + INTERVAL QUARTER(start_date) QUARTER - INTERVAL 1 DAY last_day,
                    QUARTER(start_date) quarter,
                    YEAR(start_date) year
                FROM
                    bookings
                UNION

                    SELECT
                        room_id,
                        room_bed,
                        start_date,
                        end_date,
                        DATEDIFF( end_date, GREATEST(start_date, MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 QUARTER - INTERVAL 1 DAY ) ) duration,
                        DATEDIFF(MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 DAY, MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 QUARTER ) as days_in_quarter,
                        MAKEDATE(YEAR(end_date), 1) + INTERVAL QUARTER(end_date) QUARTER - INTERVAL 1 DAY last_day,
                        QUARTER(end_date) quarter,
                        YEAR(end_date) YEAR
                    FROM
                        bookings
                    ) t
                    WHERE t.year = $year AND t.room_bed = '$bed_type'
                    GROUP BY t.year, t.quarter, t.room_bed
                    ORDER BY t.year, t.quarter
        ");

        $data = collect($data ? $data : []);

        return $data;
    }

    public function byYearByBed(string $bed_type, int $start_year = NULL, int $end_year = NULL)
    {
        $data = DB::select("
            SELECT
                SUM(t.duration) / (AVG(t.days_in_year) * (SELECT COUNT(id) FROM rooms WHERE rooms.bed_type = t.room_bed AND YEAR(created_at) <= t.year )) * 100 occupancy_rate,
                t.room_bed,
                t.year
            FROM (
                SELECT
                    room_id,
                    room_bed,
                    start_date,
                    end_date,
                    DATEDIFF( LEAST(end_date, LAST_DAY(DATE_ADD(start_date, INTERVAL 12 - MONTH(start_date) MONTH )) ), start_date ) duration,
                    DAYOFYEAR(LAST_DAY(DATE_ADD(start_date, INTERVAL 12 - MONTH(start_date) MONTH))) as days_in_year,
                    YEAR(start_date) year
                FROM
                    bookings
                UNION

                    SELECT
                        room_id,
                        room_bed,
                        start_date,
                        end_date,
                        DATEDIFF( end_date, GREATEST(start_date, MAKEDATE(YEAR(end_date), 1) ) ) duration,
                        DAYOFYEAR(LAST_DAY(DATE_ADD(end_date, INTERVAL 12 - MONTH(end_date) MONTH))) as days_in_year,
                        YEAR(end_date) year
                    FROM
                        bookings
                    ) t
                    WHERE t.room_bed = '$bed_type'
                    AND t.year BETWEEN $start_year AND $end_year
                    GROUP BY t.year, t.room_bed
                    ORDER BY t.year
        ");

        $data = collect($data ? $data :[]);

        return $this->fillYear($data, $start_year, $end_year, NULL, $bed_type);
    }

    protected function fillYear(Collection $data, $start_year, $end_year, $category = NULL, $bed_type = NULL)
    {
        $filledData = collect([]);

        for($year = $start_year; $year <= $end_year; $year++)
        {
            $byYear = function($item) use ($year)
            {
                return $item->year == $year;
            };

            $yearData = $data->pluck("year")->contains($year)
                ? $data->filter($byYear)->first()
                : [
                    "year" => $year,
                    "occupancy_rate" => 0,
                    "category" => $category,
                    "bed" => $bed_type
                ];

            $filledData->push($yearData);
        }

        return $filledData;
    }

    protected function fillMonth(Collection $data, $year)
    {
        $filledData = collect([]);

        for($month = 1; $month <= 12; $month++)
        {
            $byMonth = function($item) use($month)
            {
                return $item->month == $month;
            };

            $monthData = $data->pluck('month')->contains($month)
                ? $data->filter($byMonth)->first()
                : ["month" => $month, "occupancy_rate" => 0, "year" => $year];

            $filledData->push($monthData);
        }

        return $filledData;
    }

}
