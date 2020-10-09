<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyInventoryLedger extends CommonModel
{
    const PROPERTY_INVENTORY_ADJUSTMENT = 'Property Inventory Adjustment';
    const BOOKING = 'Booking';
    const BOOKING_CANCELLATION = 'Booking Cancellation';
}
