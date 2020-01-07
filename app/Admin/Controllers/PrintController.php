<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PrintController extends Controller
{
    public function print($entity, $entity_id)
    {
        $view = str_replace('-', '_', $entity);
        return view( 'prints.' . $view, compact('entity_id'));
    }
}