<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Satisfaction as SatisfactionModel;

class Satisfaction {

    public function average(int $year = NULL)
    {
        // return SatisfactionModel::avg('score');
        $data = SatisfactionModel::query();

        if($year) {
            $data = $data->whereYear('created_at', '=', $year);
        }

        return number_format((float)$data->avg('score'), 2);
    }

    public function countByScores($year = NULL)
    {
        $data = SatisfactionModel::select(['score', DB::raw('COUNT(id) count')])
            ->groupBy('score')
            ->orderBy('score');

        if($year) {
            $data = $data->whereYear('created_at', '=', $year);
        }

        $data = $data->get();

        $allCount = $data->sum('count');

        $addPercentage = function($item) use($allCount) {

            if($allCount == 0) {
                $item->percentage = 0;
            } else {
                $item->percentage = $item->count / $allCount * 100;
                $item->percentage = number_format((float)$item->percentage, 2, '.', '');
            }

            return $item;
        };

        return $this->fillScore($data)->map($addPercentage);
    }

    protected function fillscore(Collection $data)
    {
        $result = collect(array_fill(1, 10, NULL))->map(function($_, $index) use ($data){

            $byScore = function($item) use ($index) {
                return $item->score == $index;
            };

            $item = new \stdClass;
            $item->score = $index;
            $item->count = 0;

            return $data->pluck('score')->contains($index)
                ? $data->filter($byScore)->first()
                : $item;
        });

        return $result;
    }

    public function classified(int $year = NULL)
    {
        $data = $this->countByScores($year);

        $classified = $data->map(function($item) {
            if($item->score <=2){
                $item->class = "Very Dissatisfied";
            } else if($item->score <= 4) {
                $item->class = "Dissatisfied";
            } else if($item->score <=6) {
                $item->class = "Neutral";
            } else if($item->score <=8) {
                $item->class = "Satisfied";
            } else {
                $item->class = "Very Satisfied";
            }

            return $item;
        });

        $classified = $classified->reduce(function($carry, $item){

            $exist = $carry->contains(function($value) use($item) {
                return $value->class == $item->class;
            });

            if($exist) {
                $carry = $carry->map(function($value) use ($item) {

                    if($value->class == $item->class) {
                        $value->count += $item->count;
                        $value->percentage += $item->percentage;
                    }

                    return $value;
                });                
            } else {
                $carry->push($item);
            }

            return $carry;
        }, collect([]));

        // berikan nilai kembalian
        return $classified;
    }

    public function averageByMonths(int $year = NULL)
    {
        $data = SatisfactionModel::select([
            DB::raw('AVG(score) avg_score'),
            DB::raw('MONTH(created_at) month'),
        ])
        ->groupBy('month')
        ->orderBy('month');

        if($year){
            $data = $data->whereYear('created_at', '=', $year);
        }

        return $data->get();
    }

    public function averageByServices($year = NULL)
    {
        $data = SatisfactionModel::select([
            'services.name',
            DB::raw('AVG(satisfactions.score) avg_score')
        ])
        ->leftJoin('services', 'services.id', '=', 'satisfactions.service_id')
        ->groupBy('satisfactions.service_id');

        if($year){
            $data = $data->whereYear('satisfactions.created_at', '=', $year);
        }

        return $data->get();
    }

    public function averageByYear(int $start_year = NULL, int $end_year = NULL)
    {
        $data = SatisfactionModel::select([
            DB::raw('AVG(score) avg_score'),
            DB::raw('YEAR(created_at) year')
        ])->groupBy('year')
        ->whereYear("created_at", ">=", $start_year)
        ->whereYear("created_at", "<=", $end_year)
        ->orderBy("year");

        return $data->get();
    }

}