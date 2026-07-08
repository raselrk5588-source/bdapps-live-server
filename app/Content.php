<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    public function installApp()
    {
        return $this->belongsTo("\App\InstallApp",'app_id');
    }
}
