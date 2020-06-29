<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryUpload extends Model
{
    protected $table = 'history';

    public function user() {

        return $this->belongsTo('App\User');
    }

    public static function getHistoryInfo() {

        return self::all();
    }

    public static function getLastUpdatedDateTime() {

        $data       =   self::orderBy('created_at', 'desc')->first();

        return date('d M Y H:i', strtotime($data->created_at));
    }
}
