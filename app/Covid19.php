<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Covid19 extends Model
{
    protected $table = 'covid_19';

    public static function insertCovid19Info($data , $countryId) {

        $object                     =   new Covid19();
        $object->country_id         =   $countryId;
        $object->reported_date      =   date('Y-m-d', strtotime($data[0]));
        $object->new_cases          =   $data[4];
        $object->cumulative_cases   =   $data[5];
        $object->new_death          =   $data[6];
        $object->cumulative_death   =   $data[7];
        $object->save();

    }

    public static function getSummary() {

        $totalCases = self::sum('new_cases');
        $totalDeath = self::sum('new_death');

        return array(
            'total_cases' => $totalCases,
            'total_death' => $totalDeath
        );
    }


    public static function getSummaryByCountry($countryName) {

        $countryId  = Country::where('name', $countryName)->first();
        $countryId  = $countryId->id;

        $totalCases = self::where('country_id', $countryId)->sum('new_cases');
        $totalDeath = self::where('country_id', $countryId)->sum('new_death');

        return array(
            'total_cases' => $totalCases,
            'total_death' => $totalDeath
        );
    }

    public static function getCovid19Info($orderInfo) {

        $latestReportedDate     =   self::orderBy('reported_date','DESC')->first();

        return self::select('covid_19.*','country.name')->where('reported_date', $latestReportedDate->reported_date )
            ->join('country', 'country.id','=','covid_19.country_id')
            ->orderBy($orderInfo['column'] , $orderInfo['dir'])
            ->get();

    }

    public static function getCovid19InfoWithFilter($searchVal , $orderInfo) {

        $latestReportedDate     =   self::orderBy('reported_date','DESC')->first();

        return self::select('covid_19.*','country.name')
            ->where('reported_date', $latestReportedDate->reported_date )
            ->where(function ($q) use ($searchVal) {
                $q->where('country.name',  'like' , '%'.$searchVal.'%')
                    ->orWhere('new_cases', 'like' , '%'.$searchVal.'%')
                    ->orWhere('cumulative_cases', 'like' , '%'.$searchVal.'%')
                    ->orWhere('new_death', 'like' , '%'.$searchVal.'%')
                    ->orWhere('cumulative_death', 'like' , '%'.$searchVal.'%');
            })
            ->join('country', 'country.id','=','covid_19.country_id')
            ->orderBy($orderInfo['column'] , $orderInfo['dir'])
            ->get();

    }

    public static function getDetailedInfoByCountry($countryName) {

        return self::select('covid_19.*','country.name')
            ->join('country', 'country.id','=','covid_19.country_id')
            ->where('country.name', $countryName)
            ->orderBy('reported_date' , 'asc')
            ->get();
    }


    public static function getPieInfoByRegionOnSelectedCountry($countryName) {

        $countryInfo            =   Country::where('name',$countryName)->first();
        $latestReportedDate     =   self::orderBy('reported_date','DESC')->first();

        return self::select('covid_19.*', 'country.name')
                        ->where('reported_date',$latestReportedDate->reported_date)
                        ->join('country', 'country.id','=','covid_19.country_id')
                        ->where('region',$countryInfo->region)
                        ->get();

    }
}
