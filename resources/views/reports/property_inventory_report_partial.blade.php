<?php

$propertyInventory = \DB::table('property_files')
    ->join('projects', 'projects.id', '=', 'property_files.project_id')
    ->join('phases', 'phases.id', '=', 'property_files.phase_id')
    ->join('blocks', 'blocks.id', '=', 'property_files.block_id')
    ->join('property_types', 'property_types.id', '=', 'property_files.property_type_id')
    ->select(
        'projects.name as project_name',
        'phases.name as phase_name',
        'blocks.name as block_name',
        'property_types.name as property_type_name',
        'property_files.is_farmhouse',
        'property_files.is_corner',
        'property_files.is_facing_park',
        'property_files.is_on_boulevard',
        'property_files.marlas',
        'property_files.holder_id',
        'property_files.dealer_id',
    )
    ->get();

// dd($propertyInventory);

?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-borders">
                <h3 class="box-title">Property Inventory</h3>
            </div>
            <div class="box-body">
                <div id="property-inventory-pivot"></div>
                <table id="property-inventory-table" class="table table-bordered">
                    <thead>
                        <th>Project</th>
                        <th>Phase</th>
                        <th>Block</th>
                        <th>Size</th>
                        <th>Property Type</th>
                        <th>Farmhouse</th>
                        <th>Corner</th>
                        <th>Facing park</th>
                        <th>On boulevard</th>
                        <th>Open</th>
                        <th>Booked</th>
                    </thead>
                    <tbody>
                        @foreach($propertyInventory as $propertyInventoryRow)
                        <tr>
                            <td>{{$propertyInventoryRow->project_name}}</td>
                            <td>{{$propertyInventoryRow->phase_name}}</td>
                            <td>{{$propertyInventoryRow->block_name}}</td>
                            <td>{{ $propertyInventoryRow->marlas }}</td>
                            <td>{{$propertyInventoryRow->property_type_name}}</td>
                            <td>{{$propertyInventoryRow->is_farmhouse == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->is_corner == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->is_facing_park == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->is_on_boulevard == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->dealer_id == '' ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->holder_id == '' ? 'Yes' : 'No'}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#property-inventory-pivot").pivotUI($("#property-inventory-table"),
    {
        rows: ["Marlas"],
        cols: ["Property Type"]
    });
    $('#property-inventory-table').hide();
})
</script>
