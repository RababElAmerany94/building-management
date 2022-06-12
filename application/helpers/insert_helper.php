<?php

function insert_addExtrafields($crud, $columns, $fields, $isRead = false)
{
	$extra_fields = [
		"Id_Creator"   => "Créé Par",
		"Date_Created" => "Créé Le",
		"Id_Editor"    => "Modifié Par",
		"Date_Edited"  => "Modifié Le",
	];

	//loop extra fields and add every field to $fields & $columns variable
	foreach ($extra_fields as $key => $extra_field)
	{
		array_push($columns, $key);
		array_push($fields, $key);
		//set fields labels
		$crud->display_as($key, $extra_field);
		//make all field Hidden in add/edit page
		if (! $isRead)
		{
			$crud->field_type($key, 'hidden');
		}
	}

	return [
		'columns' => $columns,
		'fields'  => $fields
	];
}

function insert_helper_callback($post_array, $user_id)
{
	$post_array['Id_Creator'] = $user_id; //Set user_id
	$post_array['Date_Created'] = date('Y-m-d H:i:s');

	return $post_array;
}

function update_helper_callback($post_array, $user_id)
{
	$post_array['Id_Editor'] = $user_id; //Set user_id
	$post_array['Date_Edited'] = date('Y-m-d H:i:s');

	return $post_array;
}