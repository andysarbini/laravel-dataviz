<?php

namespace App\Http\Controllers;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Http\Request;
class BasicChartController extends Controller
{
    //
    public function index()
    {
        $chart = (new LarapexChart)->pieChart()
            ->setTitle('Top Skor di Tim')
            ->setSubtitle('Musim 2021.')
            ->addData([40, 50, 30])
            ->setLabels(['Pemain 7', 'Pemain 10', 'Pemain 9']);

        return view('basic-chart.index', compact('chart'));
    }

    public function barChart()
    {
        $chart = (new LarapexChart)->BarChart()
            ->setTitle('Persija vs Persita')
            ->setSubtitle('Kemenangan di musim 2021')
            ->addData('Persija', [6, 9, 3, 4, 10, 8])
            ->addData('Persita', [7, 3, 8, 2, 6, 4])
            ->setGrid(TRUE, '#f0f', 0.2)
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
        
        return view('basic-chart.index', compact('chart'));
    }

    public function donutChart()
    {
        $chart = (new LarapexChart)->donutChart()
            ->setTitle('Top Skor di Time')
            ->setSubtitle('Musim 2021')
            ->addData([40, 50, 30])
            ->addData([10,30,40])
            ->setLabels(['Pemain 7', 'Pemain 10', 'Pemain 9']);

        return view('basic-chart.index', compact('chart'));
    }

    public function radialChart()
    {
        $chart = (new LarapexChart)->radialChart()
            ->setTitle('Efektivitas Umpan')
            ->setSubtitle('Barcelona city vs Madrid sports')
            ->addData([75, 60])
            ->setLabels(['Barcelona city', 'Madrid sports'])
            ->setColors(['#D32F2F', '#03A9F4']);

        return view('basic-chart.index', compact('chart'));
    }

    public function polarAreaChart()
    {
        $chart = (new LarapexChart)->polarAreaChart()
            ->setTitle('Top Skor di Tim')
            ->setSubtitle('Musim 2021.')
            ->addData([40, 50, 30])
            ->addData([10, 30, 40])
            ->setLabels(['Pemain 7', 'Pemain 10', 'Pemain 9']);

        return view ('basic-chart.index', compact('chart'));
    }

    public function lineChart()
    {
        $chart = (new LarapexChart)->lineChart()
            ->setTitle('Persija vs Persita')
            ->setSubtitle('Kemenangan di Musim 2021.')
            ->addData('Persija', [6, 9, 3, 4, 10, 8])
            ->addData('Persita', [7, 3, 8, 2, 6, 4])
            // ->setMarkers(colors: string[#FFA41B], width = 4, hoversize = 7)
            ->setStroke(10, ['#ff0', '#f0f'])
            ->setToolbar(true, true)
            ->setDataLabels()
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);

        return view('basic-chart.index', compact('chart'));
    }

    public function areaChart()
    {
        $chart = (new LarapexChart)->areaChart()
            ->setTitle('Persija vs Persita')
            ->setSubtitle('Kemenangan di Musim 2021.')
            ->addData('Persija', [6, 9, 3, 4, 10, 9])
            ->addData('Persita', [7, 3, 8, 2, 6, 4])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);

        return view('basic-chart.index', compact('chart'));
    }

    public function heatMapChart()
    {
        $chart = (new LarapexChart)->heatMapChart()
            ->setTitle('Grafik HeatMap Dasar')
            ->addData('Penjualan', [80,  50, 30, 40, 100, 20])
            ->addHeat('Profit', [70, 10, 80, 20, 60, 40])
            ->setMarkers(['#FFA41B', '#4F46E5', 7, 10])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);

        return view('basic-chart.index', compact('chart'));
    }

    public function radarChart()
    {
        $chart = (new LarapexChart)->radarChart()
            ->setTitle('Statistik Pemain Rique Puig')
            ->setSubtitle('Musim 2021.')
            ->addData('Stats', [70, 93, 78, 97, 50, 90])
            ->setXAxis(['Pass', 'Dribble', 'Shot', 'Stamina', 'Long shots', 'Tactical'])
            ->setMarkers(['#303F9F'], 7, 10);

        return view('basic-chart.index', compact('chart'));
    }
}
