<?php

namespace App\Helpers;

class StatusesHelper
{
    public const CANCELLED = 'cancelled';
    public const AVAILABLE = 'available';
    public const BOOKED = 'booked';
    public const ALLOTTED = 'allotted';
    public const POSSESSED = 'possessed';
    public const OPEN = 'open';

    public static function statusTitle($status)
    {
        switch ($status) {
            case StatusesHelper::CANCELLED :
                return 'Cancelled';
                break;
            case StatusesHelper::AVAILABLE :
                return 'Available';
                break;
            case StatusesHelper::BOOKED :
                return 'Booked';
                break;
            case StatusesHelper::ALLOTTED :
                return 'Allotted';
                break;
            case StatusesHelper::POSSESSED :
                return 'Possessed';
                break;
            case StatusesHelper::OPEN :
                return 'Open';
                break;
                
            default:
                return 'Not Defined';
                break;
        }
    }
}