<?php

namespace App\Http\Controllers;

use App\Covid19;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public static function getSummary() {


        $summary   = Covid19::getSummary();

        return $summary;
    }

    public static function getSummaryByCountry($countryName) {

        $summary    =   Covid19::getSummaryByCountry($countryName);

        return $summary;
    }
}
