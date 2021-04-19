<?php
//PREFERENCES
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'wp_ajax_save_field_pref', array('NF5_Preferences','save_field_pref'));
add_action( 'wp_ajax_save_validation_pref', array('NF5_Preferences','save_validation_pref'));
add_action( 'wp_ajax_save_email_pref', array('NF5_Preferences','save_email_pref'));
add_action( 'wp_ajax_save_other_pref', array('NF5_Preferences','save_other_pref'));
if(!class_exists('NF5_Preferences'))
	{
	class NF5_Preferences
		{
		//PREFERENCES	
		public function save_field_pref() {
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();	
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['field_preferences'] = $_POST;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
		public function save_validation_pref() {
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['validation_preferences'] = $_POST;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
		public function save_email_pref() {
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['email_preferences'] = $_POST;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
		public function save_other_pref() {
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			$preferences = get_option('nex-forms-preferences'); 
			$preferences['other_preferences'] = $_POST;
			update_option('nex-forms-preferences',$preferences);
			die();
		}
	}
}



?>