<?php
add_action( 'ninja_forms_insert_sub', 'ninja_forms_attachment_test' );

function ninja_forms_attachment_test( $sub_id ){
	global $ninja_forms_processing;

	if( $ninja_forms_processing->get_form_setting( 'admin_attach_csv' ) == 1 ){
		$files = $ninja_forms_processing->get_form_setting( 'admin_attachments' );
		$sub_ids = array($sub_id);
		$csv = ninja_forms_export_subs_to_csv( $sub_ids, true );
		$path = tempnam( sys_get_temp_dir(), 'Sub' );
		$temp_file = fopen( $path, 'r+' );
		fwrite( $temp_file, $csv );
		fclose( $temp_file );
		$path = pathinfo( $path );
		$dir = $path['dirname'];
		$basename = $path['basename'];
		$new_name = apply_filters( 'ninja_forms_submission_csv_name', 'ninja-forms-submission' );
		if( file_exists( $dir.'/'.$new_name.'.csv' ) ){
			unlink( $dir.'/'.$new_name.'.csv' );
		}
		rename( $dir.'/'.$basename, $dir.'/'.$new_name.'.csv' );
		$file1 = $dir.'/'.$new_name.'.csv';
		array_push( $files, $file1 );
		$ninja_forms_processing->update_form_setting( 'admin_attachments', $files );		
	}
}	