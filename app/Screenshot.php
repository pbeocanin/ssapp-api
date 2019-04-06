<?php


namespace App;
use Illuminate\Database\Eloquent\Model;


class Screenshot extends Model
{
    public function bundle()
    {
        return $this->belongsTo('App\User', 'id');
    }
}