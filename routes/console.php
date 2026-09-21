<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:tenant', function () {
    $this->info('Source X Multi-Tenant Agency Management System');
})->purpose('Show application identity');
