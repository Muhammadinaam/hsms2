<?php
use Encore\Admin\Widgets\InfoBox;
$available_files_infobox = new InfoBox(
    'Available Files', 
    'file', 
    'yellow', 
    '/admin/property-files', 
    '1024');
?>

<div class="row">
    <div class="col-md-6">
        {!! $available_files_infobox !!}
    </div>
</div>