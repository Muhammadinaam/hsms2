<?php
use Encore\Admin\Widgets\InfoBox;

$available_files_infobox = new InfoBox(
    'Available Files', 
    'file', 
    'yellow', 
    '/admin/property-files', 
    \App\PropertyFile::available()
    ->count());

$files_with_dealers_infobox = new InfoBox(
    '(Open) Files Booked by Dealers', 
    'file', 
    'green', 
    '/admin/property-files', 
    \App\PropertyFile::open()
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
    \App\PropertyFile::where('status', \App\Helpers\StatusesHelper::ALLOTTED)
    ->count());

$files_with_possession_given_infobox = new InfoBox(
    'Files with possession given', 
    'file', 
    'red', 
    '/admin/property-files', 
    \App\PropertyFile::where('status', \App\Helpers\StatusesHelper::POSSESSED)
    ->count());

    $propertyInventory = \DB::table('property_inventory_ledgers')
        ->join('projects', 'projects.id', '=', 'property_inventory_ledgers.project_id')
        ->join('phases', 'phases.id', '=', 'property_inventory_ledgers.phase_id')
        ->join('property_types', 'property_types.id', '=', 'property_inventory_ledgers.property_type_id')
        ->select(
            'projects.name as project_name',
            'phases.name as phase_name',
            'property_types.name as property_type_name',
            'marlas',
            'is_farmhouse',
            'is_corner',
            'is_facing_park',
            'is_on_boulevard',
            \DB::raw('sum(quantity) as quantity')
        )
        ->groupBy(
            'project_name',
            'phase_name',
            'property_type_name',
            'marlas',
            'is_farmhouse',
            'is_corner',
            'is_facing_park',
            'is_on_boulevard',
        )
        ->get();

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

@component('reports.property_inventory_report_partial')
@endcomponent