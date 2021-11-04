<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Behaviour;
use ArielMejiaDev\LarapexCharts\LarapexChart;

use App\Models\Guest;
use App\Models\Room;
use App\Models\Booking;

// use App\Models\Guest;

class BehaviourController extends Controller
{
    //
    public function __construct(Behaviour $behaviour)
    {
        $this->behaviour = $behaviour;
    }

    public function rooms(Request $request)
    {
        // return $this->behaviour->rooms(2016);
        $year = $request->year;

        $rooms_overview = (new LarapexChart)->donutChart();
        $rooms_overview->setTitle("Room Selection Overview");
        $rooms_overview->addData($this->behaviour->rooms($year)->pluck('count')->toArray());
        $rooms_overview->setLabels($this->behaviour->rooms($year)->pluck('room_category')->toArray());

        $guest_origins = Guest::distinct()->get('origin')->pluck('origin')->toArray();
        $rooms_category = Room::distinct()->orderBy('category')->get('category')->pluck('category')->toArray();

        $rooms_by_guest_origin = (new LarapexChart)->barChart();
        $rooms_by_guest_origin->setTitle("Room Selection by Guest Origin");
        $rooms_by_guest_origin->setLabels($guest_origins);

        // $age_ranges = ['< 20', '20 - 29', '30 - 39', '40 - 49', '50 - 59', '> 60'];
        $age_ranges = $this->behaviour->getAgeRanges($year);

        $rooms_by_age = (new LarapexChart)->barChart();
        $rooms_by_age->setTitle("Room Selection By Age Range");
        $rooms_by_age->setLabels($age_ranges);

        $rooms_by_guest_type = (new LarapexChart)->barChart();
        $rooms_by_guest_type->setTitle("Room Selection by Guest Type");        
        $guest_types = Guest::distinct()->get('type')->pluck('type')->toArray();
        $rooms_by_guest_type->setLabels($guest_types);

        for($i = 0; $i < count($rooms_category); $i++)
        {
            $category = $rooms_category[$i];
            $rooms_by_guest_origin->addData($category, $this->behaviour->roomsByGuestOrigin($category, $year)->pluck('count')->toArray());
            $rooms_by_age->addData($category, $this->behaviour->roomsByAgeRange($category, $year)->pluck('count')->toArray());
            $rooms_by_guest_type->addData($category, $this->behaviour->roomsByGuestType($category, $year)->pluck('count')->toArray());
        }

        return view(
            'behaviour.rooms',
            compact(
                'rooms_overview',
                'rooms_by_guest_origin',
                'rooms_by_age',
                'rooms_by_guest_type'
            )
        );
    }

    public function duration(Request $request)
    {
        $year = $request->year;

        $addNights = function($dur){
            return "$dur night(s)";
        };

        $duration_overview = (new LarapexChart)->donutChart();
        $duration_overview->setTitle('Stay Duration Overview');
        $duration_overview->addData($this->behaviour->duration($year)->pluck('count')->toArray());
        $duration_overview->setLabels($this->behaviour->duration($year)->pluck('duration')->map($addNights)->toArray());

        $duration_by_guest_origin = (new LarapexChart)->barChart();
        $duration_by_guest_origin->setTitle('Stay Duration By Guest Origin');
        $guest_origins = Guest::distinct()->get('origin')->pluck('origin')->toArray();
        $duration_by_guest_origin->setLabels($guest_origins);

        $duration_by_age = (new LarapexChart)->barChart();
        $duration_by_age->setTitle('Stay Duration By Age Range');
        $age_ranges = $this->behaviour->getAgeRanges($year);
        $duration_by_age->setLabels($age_ranges);

        $duration_by_guest_type = (new LarapexChart)->barChart();
        $duration_by_guest_type->setTitle('Stay Duration By Guest Type');
        $guest_types = Guest::distinct()->get('type')->pluck('type')->toArray();
        $duration_by_guest_type->setLabels($guest_types);

        $durations = Booking::distinct()
            ->orderBy('duration')
            ->get('duration')
            ->pluck('duration')
            ->map($addNights)
            ->toArray();

        for($i = 0; $i < count($durations); $i++){
            // dapatkan durasi di tiap-tiap item array
            $duration = $durations[$i];
            $duration_by_guest_origin->addData($duration, $this->behaviour->durationByOrigin($duration, $year)->pluck('count')->toArray());
            $duration_by_age->addData($duration, $this->behaviour->durationByAgeRange($duration, $year)->pluck('count')->toArray());
            $duration_by_guest_type->addData($duration, $this->behaviour->durationByGuestType($duration, $year)->pluck('count')->toArray());
        }


        return view(
            'behaviour.duration',
            compact(
                'duration_overview',
                'duration_by_guest_origin',
                'duration_by_age',
                'duration_by_guest_type'
            )
        );
    }
    
}
