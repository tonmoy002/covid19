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
}
