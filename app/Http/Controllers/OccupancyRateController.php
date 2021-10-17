<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OccupancyRate;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class OccupancyRateController extends Controller
{
    //
    public function __construct(OccupancyRate $occupancyRate)
    {
        $this->occupancyRate = $occupancyRate;
    }

    public function index(Request $request)
    {
        // $occupancy_per_month = $this->occupancyRate->byMonth(2020);
        $start_year = $request->start;
        $end_year = $request->end;

        if($start_year == NULL) $start_year = Carbon::now()->subYear(5)->year;
        if($end_year == NULL) $end_year = date("Y");

        if($end_year - $start_year > 5) {
            $end_year = Carbon::createFromDate($start_year + 5)->year;
        }

        $year_ranges = [];
        for($year = $start_year; $year <= $end_year; $year++)
            {
                $year_ranges[] = $year;
            }

        // return $occupancy_per_month;
        $occupancy_by_month = (new LarapexChart)->lineChart();
        $occupancy_by_month->setTitle('Occupancy Rate');
        $occupancy_by_month->setXAxis(["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sept", "Okt", "Nov", "Des"]);
        
        // $occupancy_by_month->addData('2016', $this->occupancyRate->byMonth(2016)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_month->addData('2017', $this->occupancyRate->byMonth(2017)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_month->addData('2018', $this->occupancyRate->byMonth(2018)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_month->addData('2019', $this->occupancyRate->byMonth(2019)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_month->addData('2020', $this->occupancyRate->byMonth(2020)->pluck('occupancy_rate')->toArray());
        
        
        
        $occupancy_by_quarter = (new LarapexChart)->lineChart();
        $occupancy_by_quarter->setXAxis(["Q1", "Q2", "Q3", "Q4"]);
        
        $occupancy_by_year = (new LarapexChart)->lineChart();
        $occupancy_by_year->addData("Occupancy Rate", $this->occupancyRate->byYear($start_year, $end_year)->pluck('occupancy_rate')->toArray());
        $occupancy_by_year->setXAxis($year_ranges);

        for($year = $start_year; $year <= $end_year; $year++)
        {
            $occupancy_by_month->addData($year, $this->occupancyRate->byMonth($year)->pluck('occupancy_rate')->toArray());
            $occupancy_by_quarter->addData($year, $this->occupancyRate->byQuarter($year)->pluck('occupancy_rate')->toArray());

        }
        // $occupancy_by_year->addData('2016 - 2020', $this->occupancyRate->byYear(2016, 2020)->pluck('occupancy_rate')->toArray());

        // $occupancy_by_quarter->addData('2016', $this->occupancyRate->byQuarter(2016)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_quarter->addData('2017', $this->occupancyRate->byQuarter(2017)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_quarter->addData('2018', $this->occupancyRate->byQuarter(2018)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_quarter->addData('2019', $this->occupancyRate->byQuarter(2019)->pluck('occupancy_rate')->toArray());
        // $occupancy_by_quarter->addData('2020', $this->occupancyRate->byQuarter(2020)->pluck('occupancy_rate')->toArray());


        return view(
                'occupancy-rate.index', 
            compact(
                'occupancy_by_month',
                'occupancy_by_quarter',
                'occupancy_by_year'
            )
        );
        // return view('basic-chart.index', compact('chart'));
    }
}
