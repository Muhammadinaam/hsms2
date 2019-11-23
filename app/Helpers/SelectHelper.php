<?php

namespace App\Helpers;

class SelectHelper
{
    public static function selectedOptionData($model_name, $id)
    {
        $data = $model_name::find($id);
        
        if ($data) {
            return [$data->id => $data->text_for_select];
        }
    }

    public static function selectModelUrl($model_name, $where_clauses = '')
    {
        $where_clauses = $where_clauses != '' ? '&where_clauses=' . $where_clauses : '';  
        return url('select-data-model?model=' . urlencode($model_name)) . $where_clauses;
    }
}