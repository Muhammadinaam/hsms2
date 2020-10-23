<?php
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
    \DB::raw('sum(if( quantity>0 , quantity , 0 )) as total_quantity'),
    \DB::raw('sum(if( quantity<0 , -quantity , 0 )) as booked_quantity'),
    \DB::raw(
        '(select count(*) from property_files where dealer_id is not null '.
        'and property_files.project_id = projects.id '.
        'and property_files.phase_id = phases.id '.
        'and property_files.property_type_id = property_types.id '.
        'and property_files.marlas = property_inventory_ledgers.marlas '.
        'and property_files.is_farmhouse = property_inventory_ledgers.is_farmhouse '.
        'and property_files.is_corner = property_inventory_ledgers.is_corner '.
        'and property_files.is_facing_park = property_inventory_ledgers.is_facing_park '.
        'and property_files.is_on_boulevard = property_inventory_ledgers.is_on_boulevard '.
        ')as open_quantity '
    ),
    \DB::raw('sum(quantity) as balance_quantity')
)
->groupBy(
    'projects.id',
    'phases.id',
    'property_types.id',
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
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-borders">
                <h3 class="box-title">Property Inventory</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <th>Project</th>
                        <th>Phase</th>
                        <th>Marlas</th>
                        <th>Property Type</th>
                        <th>Farmhouse</th>
                        <th>Corner</th>
                        <th>Facing park</th>
                        <th>On boulevard</th>
                        <th>Total</th>
                        <th>Booked</th>
                        <th>Open</th>
                        <th>Balance</th>
                    </thead>
                    <tbody>
                        @foreach($propertyInventory as $propertyInventoryRow)
                        <tr>
                            <td>{{$propertyInventoryRow->project_name}}</td>
                            <td>{{$propertyInventoryRow->phase_name}}</td>
                            <td>{{$propertyInventoryRow->marlas}}</td>
                            <td>{{$propertyInventoryRow->property_type_name}}</td>
                            <td>{{$propertyInventoryRow->is_farmhouse == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->is_corner == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->is_facing_park == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->is_on_boulevard == 1 ? 'Yes' : 'No'}}</td>
                            <td>{{$propertyInventoryRow->total_quantity}}</td>
                            <td>{{$propertyInventoryRow->booked_quantity}}</td>
                            <td>{{$propertyInventoryRow->open_quantity}}</td>
                            <td>{{$propertyInventoryRow->balance_quantity - $propertyInventoryRow->open_quantity}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>