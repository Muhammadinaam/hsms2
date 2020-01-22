<?php
use Encore\Admin\Widgets\InfoBox;

$available_files_infobox = new InfoBox(
    'Available Files', 
    'file', 
    'yellow', 
    '/admin/property-files', 
    \App\PropertyFile::where('status', \App\Helpers\StatusesHelper::AVAILABLE)
    ->whereNull('dealer_id')
    ->count());

$files_with_dealers_infobox = new InfoBox(
    'Files Booked by Dealers', 
    'file', 
    'green', 
    '/admin/property-files', 
    \App\PropertyFile::where('status', \App\Helpers\StatusesHelper::AVAILABLE)
    ->whereNotNull('dealer_id')
    ->count());

$files_booked_by_customers_infobox = new InfoBox(
    'Files Booked by Customers', 
    'file', 
    'blue', 
    '/admin/property-files', 
    \App\PropertyFile::where('status', \App\Helpers\StatusesHelper::BOOKED)
    ->count());

$files_with_plots_allotted_infobox = new InfoBox(
    'Files with plots allotted', 
    'file', 
    'aqua', 
    '/admin/property-files', 
    \App\PropertyFile::where('status', \App\Helpers\StatusesHelper::BOOKED)
    ->count());

$files_with_possession_given_infobox = new InfoBox(
    'Files with possession given', 
    'file', 
    'red', 
    '/admin/property-files', 
    \App\PropertyFile::where('status', \App\Helpers\StatusesHelper::BOOKED)
    ->count());

?>

<div class="row">
    <div class="col-md-6">
        {!! $available_files_infobox !!}
    </div>
    <div class="col-md-6">
        {!! $files_with_dealers_infobox !!}
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        {!! $files_booked_by_customers_infobox !!}
    </div>
    <div class="col-md-4">
        {!! $files_with_plots_allotted_infobox !!}
    </div>
    <div class="col-md-4">
    {!! $files_with_possession_given_infobox !!}
    </div>
</div>