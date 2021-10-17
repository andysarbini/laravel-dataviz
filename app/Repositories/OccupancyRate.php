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

    return $data;
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
        return $data;
        }
}
