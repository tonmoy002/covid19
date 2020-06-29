<?php

namespace App\Http\Controllers;

use App\Covid19;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use stdClass;

class Covid19Controller extends Controller
{

    public function homePage() {

        $summary        =   SummaryController::getSummary();
        return view('welcome', compact('summary'));

    }

    public function getAllCovid19Data(Request $request) {

        $columns        =   array(
            '0' =>  'name',
            '1' =>  'new_cases',
            '2' =>  'cumulative_cases',
            '3' =>  'new_death',
            '4' =>  'cumulative_death',
        );

        if($request->ajax()) {

            $search  =  $request->get('search');
            $order   =  $request->get('order');
            $orderInfo['column']      =   $columns[$order[0]['column']];
            $orderInfo['dir']         =   $order[0]['dir'];
            $limit                    =   $request->get('length');
            $start                    =   $request->get('start');

            //print_r($request->get('draw'));exit;

            if(empty($search['value'])) {
                $list           =   Covid19::getCovid19Info($orderInfo);
            }else{
                $list           =   Covid19::getCovid19InfoWithFilter($search['value'], $orderInfo);

            }


            return array(
                "draw"=> intval($request->get('draw')),
                "recordsTotal"=> intval(count($list)),
                "recordsFiltered"=> intval(count($list)),
                "data" => $list
            );
        }
    }

    public function getCovidDataBasedOnCountry($countryName) {


        $summary        =   SummaryController::getSummaryByCountry($countryName);


        return view('covid_by_country', compact(['summary', 'countryName']));
    }

    public function getCovid19DataByCountryForChart(Request $request) {


        $countryName    =   $request->get('country_name');
        $covidDatas     =   Covid19::getDetailedInfoByCountry($countryName);

        $dates              =   array();
        $cumulativeCases    =   array();
        $cumulativeDeath    =   array();

        foreach ($covidDatas as $data) {

            $dates[]                =   date('d M Y' , strtotime($data->reported_date) );
            $cumulativeCases[]      =   $data->cumulative_cases;
            $cumulativeDeath[]      =   $data->cumulative_death;

        }

        $deathInRegion              =   array();
        $casesRegion                =   array();

        $pieDatas                   =   Covid19::getPieInfoByRegionOnSelectedCountry($countryName);

        foreach ($pieDatas as $data) {

            $deathObject        =   new stdClass();
            $deathObject->name  =   $data->name;
            $deathObject->y     =   $data->cumulative_death;
            $deathObject->drilldown = $data->name;

            $casesObject        =   new stdClass();
            $casesObject->name  =   $data->name;
            $casesObject->y     =   $data->cumulative_cases;
            $casesObject->drilldown = $data->name;

            $deathInRegion[]    =   $deathObject;
            $casesRegion[]      =   $casesObject;

        }



        return array(

            'dates' =>   $dates,
            'cumulative_cases'  =>   $cumulativeCases,
            'cumulative_death'  =>   $cumulativeDeath,
            'death_region'      =>   $deathInRegion,
            'cases_region'      =>   $casesRegion
        );

    }

}
