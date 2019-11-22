<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class SelectDataController extends Controller
{
    public function selectData()
    {
        $q = request()->get('q');
        $model_data = explode('|', request()->model);

        $model_name = $model_data[0];
        $model_search_field = explode(',', $model_data[1]);
        $model_shown_columns = explode(',', $model_data[2]);

        $model = new $model_name();

        foreach($model_search_field as $search_field)
        {
            $model = $model->orWhere($search_field, 'like', '%'.$q.'%');
        }

        $data = $model->paginate()->toArray();

        $formatted_data = [];
        foreach($data['data'] as $row)
        {
            $formatted_row = [];
            $formatted_row['id'] = $row['id'];
            $text = '';
            foreach($model_shown_columns as $shown_column)
            {
                $shown_column = explode(':', $shown_column);
                $shown_column_db_name = $shown_column[0];
                $shown_column_title = $shown_column[1];
                
                $text .= $shown_column_title . ': ' . $row[$shown_column_db_name] . ', ';
            }
            $text = substr($text, 0, strlen($text) - 2);
            $formatted_row['text'] = $text;

            $formatted_data[] = $formatted_row;
        }
        $data['data'] = $formatted_data;

        return $data;
    }
}
