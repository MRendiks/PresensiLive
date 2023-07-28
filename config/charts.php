<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default library used in charts.
    |--------------------------------------------------------------------------
    |
    | This value is used as the default chart library used when creating
    | any chart in the command line. Feel free to modify it or set it up
    | while creating the chart to ignore this value.
    |
    */
    'default_library' => 'Chartjs',

     /*
    |--------------------------------------------------------------------------
    | Global Route Prefix
    |--------------------------------------------------------------------------
    |
    | This option allows to modify the prefix used by all the chart routes.
    | It will be applied to each and every chart created by the library. This
    | option comes with the default value of: 'api/chart'. You can still define
    | a specific route prefix to each individual chart that will be applied after this.
    |
    */
    'global_route_prefix' => 'api/chart',

    /*
    |--------------------------------------------------------------------------
    | Global Middlewares.
    |--------------------------------------------------------------------------
    |
    | This option allows to apply a list of middlewares to each and every
    | chart created. This is commonly used if all your charts share some
    | logic. For example, you might have all your charts under authentication
    | middleware. If that's the case, applying a global middleware is a good
    | choice rather than applying it individually to each chart.
    |
    */
    'global_middlewares' => ['web'],

    'global_route_name_prefix' => 'charts',
];
