<?php

namespace App\Helpers;

use Illuminate\Support\MessageBag;

class UpdateHelpers
{
    public static function isUpdateAllowed($entity_title, $entity, $status_field_name, $status_which_allows_update)
    {
        if($entity != null && $entity->{$status_field_name} != $status_which_allows_update)
        {
            $error = new MessageBag([
                'title'   => 'Error',
                'message' => 'Status of ' . $entity_title . ' is ['.$entity->{$status_field_name}.']. It cannot be edited now.',
            ]);
        
            return back()->with(compact('error'));
        }

        return true;
    }

    public static function UpdateStatusLogic(
        $entity_title,
        $entity, 
        $entity_model_class,
        $status_field_name,
        $status_editable_condition,
        $current_model,
        $entity_id_field_name_in_current_model,
        $revert_status,
        $new_status)
    {
        if($status_editable_condition )
        {
            $error = new MessageBag([
                'title'   => 'Error',
                'message' => 'Status of this '.$entity_title.' is ' . $entity->{$status_field_name} . '. This cannot be changed now',
            ]);
        
            return back()->with(compact('error'));    
        } 

        if($current_model != null)
        {
            $previous_entity_id = $current_model->{$entity_id_field_name_in_current_model};
            if($previous_entity_id != $entity->id) 
            {
                //revert previous entity if editing this form
                $previous_entity = $entity_model_class::find($previous_entity_id);
                $previous_entity->{$status_field_name} = $revert_status;
                $previous_entity->save();
            }
        }

        $entity->{$status_field_name} = $new_status;
        $entity->save();

        return true;
    }
}