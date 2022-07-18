<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TrackService{

    private $lat;

    private $lon;

    private $mmsi;

    private $time_interval;

    public function __construct(Request $request)
    {
        $this->lat = $request->lat;
        $this->lon = $request->lon;
        $this->mmsi = $request->mmsi;
        $this->time_interval = $request->time_interval;
    }

    public function filter(Model $vesselPosition){
        $model = $vesselPosition->query();

        if ($this->mmsi){
            $mmsi_array = is_array($this->mmsi) ? $this->mmsi : explode(",", $this->mmsi);
            

            $model->whereIn('mmsi', $mmsi_array);
        }

        if ($this->time_interval){
            $time_range = is_array($this->time_interval) ? $this->time_interval : explode(",", $this->time_interval);
            if (count($time_range) != 2){
                throw new Exception("Time interval must contain exactly 2 values");
            }
            sort($time_range);
            $model->whereBetween("timestamp", $time_range);
        }

        return $model;
    }
}