<?php

function integer_validation($crud,$fields,$required_fields){

   //Loop for all tables fileds
   foreach($fields as $type=> $field) {
       if( $field->type == 'int' || $field->type == 'decimal' || $field->type == 'smallint') {
          //check if the fields is required,
          if(in_array($field->name, $required_fields)) {
            $crud->set_rules($field->name,$field->name,'required|numeric');
          } else {
            $crud->set_rules($field->name,$field->name,'numeric');
          }
       }
   }
}
