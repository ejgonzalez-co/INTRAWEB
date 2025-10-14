<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timout:
    |
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */

    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTML_PDF_BINARY', env('APP_ENV') == "local" ? '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"' : '/usr/local/bin/wkhtmltopdf'), // Windows
        // 'binary'  => env('WKHTML_PDF_BINARY', '/usr/local/bin/wkhtmltopdf'), // Linux
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', env('APP_ENV') == "local" ? '"C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe"' : '/usr/local/bin/wkhtmltoimage'), // Windows
        // 'binary'  => env('WKHTML_IMG_BINARY', '/usr/local/bin/wkhtmltoimage'), // Linux
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
