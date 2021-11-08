<?php

namespace App\Http\Controllers;

use App\Repositories\Satisfaction;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SatisfactionController extends Controller
{
    //
    public function __construct(Satisfaction $satisfaction)
    {
        $this->satisfaction = $satisfaction;
    }

    public function index(Request $request)
    {

        // menangkap query string sbg variabel
        $year = $request->year;
        $start_year = $request->start;
        $end_year = $request->end;

        // berikan nilai default
        if($start_year == NULL) $start_year = Carbon::now()->subYear(5)->year;
        if($end_year == NULL) $end_year = date("Y");

        $satisfaction_avg = $this->satisfaction->average($year);
        // buat objek grafik
        $satisfaction_by_scores = (new LarapexChart)->horizontalBarChart();
        $satisfaction_by_scores->setTitle('Count by Scores');

        // ekstrak nilai percentage dr masing2 kolom
        $count_by_scores_data = $this
            ->satisfaction
            ->countByScores($year)
            ->pluck('percentage')
            ->toArray();

        // isi hasilnya kedalam grafik
        $satisfaction_by_scores->addData('percentage', array_reverse($count_by_scores_data));
        $satisfaction_by_scores->setLabels(array_reverse(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']));

        $satisfaction_classified_data = $this->satisfaction->classified($year);
        $satisfaction_classified = (new LarapexChart)->donutChart();
        $satisfaction_classified->addData($satisfaction_classified_data->pluck('count')->toArray());
        $satisfaction_classified->setLabels($satisfaction_classified_data->pluck('class')->toArray());

        $formatFloat = function ($item){
            return number_format((float)$item, 2, ".","");
        };

        $satisfaction_by_months = (new LarapexChart)->areaChart();
        $satisfaction_by_months->setLabels(["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sept", "Okt", "Nov", "Des"]);
        $satisfaction_by_months->addData($year ? $year : 'All Time', $this->satisfaction->averageByMonths($year)->pluck('avg_score')->map($formatFloat)->toArray());
        $satisfaction_by_months->setMarkers();
        $satisfaction_by_months->setGrid();
        $satisfaction_by_months->setDataLabels();

        $satisfaction_by_services = (new LarapexChart)->radarChart();
        $satisfaction_by_services->setTitle('Satisfaction by Services');
        $satisfaction_by_services->addData('Score', $this->satisfaction->averageByServices($year)->pluck('avg_score')->map($formatFloat)->toArray());
        $satisfaction_by_services->setLabels($this->satisfaction->averageByServices($year)->pluck('name')->toArray());

        $satisfaction_yoy_data = $this->satisfaction->averageByYear($start_year, $end_year);
        $satisfaction_yoy = (new LarapexChart)->lineChart();
        $satisfaction_yoy->setTitle('Year on year');
        $satisfaction_yoy->addData('', $satisfaction_yoy_data->pluck('avg_score')->map($formatFloat)->toArray());
        $satisfaction_yoy->setLabels($satisfaction_yoy_data->pluck('year')->toArray());
        $satisfaction_yoy->setDataLabels();

        // lempar ke view
        return view(
            'satisfaction.index',
            compact(
                'satisfaction_avg',
                'satisfaction_by_scores',
                'satisfaction_classified_data',
                'satisfaction_classified',
                'satisfaction_by_months',
                'satisfaction_by_services',
                'satisfaction_yoy'
            ));

    }
}
