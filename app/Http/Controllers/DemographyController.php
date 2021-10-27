<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Demography;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

use App\Models\Guest;

class DemographyController extends Controller
{
    //
    public function __construct(Demography $demography)
    {
        $this->demography = $demography;
        
    }

    public function index(Request $request)
    {

        $start_year = $request->start;
        $end_year = $request->end;
        $quarter_year = $request->quarter_year;
        $month_year = $request->month_year;

        if($end_year - $start_year > 5)
        {
            $end_year = Carbon::createFromDate($start_year + 5)->year;
        }

        if($start_year == NULL) $start_year = Carbon::now()->subYear(5)->year;
        if($end_year == NULL) $end_year = date("Y");
        if($quarter_year == NULL) $quarter_year = date("Y");
        if($month_year == NULL) $quarter_year = date("Y");

        $year_ranges = [];
            for($year = $start_year; $year <= $end_year; $year++)
            {
                $year_ranges[] = $year;
            }
        

        $dg_by_age = (new LarapexChart)->horizontalBarChart();
        $dg_by_age->addData('Count', $this->demography->byAge()->pluck('count')->toArray());
        $dg_by_age->setLabels($this->demography->byAge()->pluck('age_range')->toArray());
        $dg_by_age->setTitle("Guest by Age Range");
        
        $dg_by_age_donut = (new LarapexChart)->donutChart();
        $dg_by_age_donut->addData($this->demography->byAge()->pluck('count')->toArray());
        $dg_by_age_donut->setLabels($this->demography->byAge()->pluck('age_range')->toArray());
        $dg_by_age_donut->setTitle("Guest by Age Range");

        $dg_by_age_by_month = (new LarapexChart)->barChart();
        $dg_by_age_by_month->setXAxis(["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Okt", "Nov", "Des"]);

        $dg_by_age_by_quarter = (new LarapexChart)->barChart();
        $dg_by_age_by_quarter->setXAxis(["Q1", "Q2", "Q3", "Q4"]);

        $dg_by_age_by_year = (new LarapexChart)->barChart();
        $dg_by_age_by_year->setXAxis($year_ranges);

        $age_ranges = ['< 20', '20 - 29', '30 - 39', '40 - 49', '50 - 59', '> 60'];

        for($i = 0; $i < count($age_ranges); $i++)
        {
            // dapatkan current range;
            $range = $age_ranges[$i];
            $dg_by_age_by_month->addData($range, $this->demography->byMonthByAge($range, $month_year)->pluck('count')->toArray());
            $dg_by_age_by_quarter->addData($range, $this->demography->byQuarterByAge($range, $month_year)->pluck('count')->toArray());
            $dg_by_age_by_year->addData($range, $this->demography->byYearByAge($range, $start_year, $end_year)->pluck('count')->toArray());

        }

        return view(
            'demography.index',
            compact(
                'dg_by_age',
                'dg_by_age_donut',
                'dg_by_age_by_month',
                'dg_by_age_by_quarter',
                'dg_by_age_by_year'
            )
            );
    }

    public function byGuestType(Request $request)
    {
        // return $this->demography->byQuarterByGuestType('couple', 2020);
        $start_year = $request->start;
        $end_year = $request->end;
        $quarter_year = $request->quarter_year;
        $month_year = $request->month_year;

        if($end_year - $start_year > 5)
        {
            $end_year = Carbon::createFromDate($start_year + 5)->year;
        }

        if($start_year == NULL) $start_year = Carbon::now()->subYear(5)->year;
        if($end_year == NULL) $end_year = date("Y");
        if($quarter_year == NULL) $quarter_year = date("Y");
        if($month_year == NULL) $quarter_year = date("Y");

        $year_ranges = [];
            for($year = $start_year; $year <= $end_year; $year++)
            {
                $year_ranges[] = $year;
            }
           
        $dg_by_type = (new LarapexChart)->horizontalBarChart();
        $dg_by_type->addData('Count', $this->demography->byGuestType()->pluck('count')->toArray());
        $dg_by_type->setLabels($this->demography->byGuestType()->pluck('guest_type')->toArray());
        $dg_by_type->setTitle("Guest by Type");

        $dg_by_type_donut = (new LarapexChart)->donutChart();
        $dg_by_type_donut->addData($this->demography->byGuestType()->pluck('count')->toArray());
        $dg_by_type_donut->setLabels($this->demography->byGuestType()->pluck('guest_type')->toArray());
        $dg_by_type_donut->setTitle("Guest by Age Range");

        $dg_by_type_by_month = (new LarapexChart)->barChart();
        $dg_by_type_by_month->setXAxis(["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Okt", "Nov", "Des"]);
        
        $dg_by_type_by_quarter = (new LarapexChart)->barChart();
        $dg_by_type_by_quarter->setXAxis(["Q1", "Q2", "Q3", "Q4"]);

        $dg_by_type_by_year = (new LarapexChart)->barChart();
        $dg_by_type_by_year->setXAxis($year_ranges);
        
        $guest_types = Guest::distinct()->get(['type'])->pluck('type')->toArray();

        for($i = 0; $i < count($guest_types); $i++) {
            // dapatkan nilai dari guest_type saat ini / current
            $guest_type = $guest_types[$i];
            $dg_by_type_by_month->addData($guest_type, $this->demography->byMonthByGuestType($guest_type, $month_year)->pluck('count')->toArray());
            $dg_by_type_by_quarter->addData($guest_type, $this->demography->byQuarterByGuestType($guest_type, $quarter_year)->pluck('count')->toArray());
            $dg_by_type_by_year->addData($guest_type, $this->demography->byYearByGuestType($guest_type, $start_year, $end_year)->pluck('count')->toArray());

        }

        return view(
            'demography.guest-type',
            compact(
                'dg_by_type',
                'dg_by_type_donut',
                'dg_by_type_by_month',
                'dg_by_type_by_quarter',
                'dg_by_type_by_year'
            )
        );
    }
}
