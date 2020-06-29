<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    public static function getSingleRowByCountryName($name) {

        return self::where('name', $name)->first();

    }

    public static function insertCountryAndReturnId($data) {

        $object                 =   new Country();
        $object->name           =   $data[2];
        $object->country_code   =   $data[1];
        $object->region         =   $data[3];
        $object->save();

        return $object->id;
    }
}
