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

        // lempar ke view
        return view(
            'satisfaction.index',
            compact(
                'satisfaction_avg',
                'satisfaction_by_scores',
            ));

    }
}
