<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Festivos extends Facade 
{
	protected static function getFacadeAccessor()
    {
        return 'Festivos';
    }
}