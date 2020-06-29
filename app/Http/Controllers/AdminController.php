<?php

namespace App\Http\Controllers;

use App\Country;
use App\Covid19;
use App\HistoryUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends Controller
{
    public function importExcelData(Request $request) {

        if($request->file('data-file')) {

            $file  = $request->file('data-file');
            $ext   = $file->getClientOriginalExtension();

            if($ext != 'xlsx' && $ext != 'xls' && $ext != 'csv'){

                return back()->with('error', 'Wrong file');
            }

            $reader = IOFactory::createReader(ucfirst($ext));
            $spreadsheet = $reader->load($file->getRealPath());
            $datas       = $spreadsheet->getActiveSheet()->toArray();

            foreach ($datas as $key=>$data) {


                if($key == 0) {

                    if($data[0] != 'Date_reported' || $data[1] != 'Country_code' || $data[2] != 'Country' || $data[3] != 'WHO_region' || $data[4] != 'New_cases' ||  $data[5] != 'Cumulative_cases' || $data[6] != 'New_deaths' ||$data[7] != 'Cumulative_deaths' ) {

                        return back()->with('error', 'Please provide supported excel');
                    }

                    Covid19::truncate();

                } else {

                    if($data[2] == 'undefined') continue;

                    $countryData   = Country::getSingleRowByCountryName($data[2]);
                    if(empty($countryData)) {

                        $countryID = Country::insertCountryAndReturnId($data);
                    }else{
                        $countryID = $countryData->id;
                    }

                    Covid19::insertCovid19Info($data , $countryID);

                }

            }

            $objectHistory              =    new HistoryUpload();
            $objectHistory->user_id     =    Auth::user()->id;
            $objectHistory->save();

            return back()->with('success', 'Updated information');





        }else{


        }

    }
}
