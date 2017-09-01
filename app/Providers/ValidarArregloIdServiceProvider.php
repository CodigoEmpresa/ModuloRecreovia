<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use DB;

class ValidarArregloIdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('exists_multiple', function($attribute, $value, $parameters, $validator) {
            $total = DB::table($parameters[0])
                ->whereIn($parameters[1], explode(',', $value))
                ->count();

            return $total == count(explode(',', $value));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
