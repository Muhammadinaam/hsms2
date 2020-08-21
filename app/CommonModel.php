<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonModel extends Model
{
    protected $appends = ['text_for_select'];
    protected static $relationMethods = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_by = \Auth::guard('admin')->user()->id;
        });

        self::saving(function ($model) {
            if($model->id != null ) {
                $model->updated_by = \Auth::guard('admin')->user()->id;
            }
        });

        self::deleting(function ($model) {
            foreach (self::$relationMethods as $relationMethod) {
                if ($model->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    public function searchForSelect($search_term, $where_clauses)
    {
        if($where_clauses == '') 
        {
            return $this;
        }

        $data = $this
            ->where(function($query) use ($search_term, $where_clauses) {
                $query->whereRaw($where_clauses);
            });

        return $data;
    }

    public function getTextForSelectAttribute()
    {
        return 'Id: ' . $this->id;
    }

    public function createdBy()
    {
        return $this->belongsTo('\Encore\Admin\Auth\Database\Administrator', 'created_by');
    }
}
