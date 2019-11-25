<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class SelectDataController extends Controller
{
    public function selectDataModel()
    {
        $q = request()->get('q');
        $where_clauses = request()->get('where_clauses');
        $model = request()->model;
        $model = new $model();

        return $model->searchForSelect($q, $where_clauses)->paginate();
    }
}
