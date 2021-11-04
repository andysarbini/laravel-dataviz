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

}