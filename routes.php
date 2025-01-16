<?php

Route::get('robots.txt', function() {
    $settings = \CRSCompany\FrameworCUtils\Models\UtilsSetting::instance();
    $robots_mode = $settings->robots_txt_mode;

    Header('Content-Type: text/plain');

    if ($robots_mode == 'custom') {
        echo $settings->robots_txt;
        exit;
    }

    /* Deny all */
    echo "User-agent: *\nDisallow: /\n";
    exit;
});
