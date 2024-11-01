<?php
/*
Plugin Name: WPLog Viewer
Version: 0.1
Plugin URI: https://launchpad.net/wplog
Description: WPLog is viewer for the logs from operators of amateur radio stations.
Author: Alex L.
Author URI: https://launchpad.net/~alexal
*/ 

function wplog_viewer_install(){
  global $wpdb;
  
  if(!get_option( 'wplog_viewer_version' )) add_option ('wplog_viewer_version' , '0.1');
  
  if(!WPLOG_VIEWER_VERSION) define('WPLOG_VIEWER_VERSION','0.1');
  update_option('wplog_viewer_version', WPLOG_VIEWER_VERSION);
  
  $table = "calls_list";
  $attribute[$table] = array('id' => 'int(9) NOT NULL AUTO_INCREMENT',
                              'callsign_id' => 'INT(9) NOT NULL',
                              'qso_date' => 'DATE NOT NULL',
                              'time_on' => 'TIME NOT NULL',
                              'call' => 'VARCHAR(10) NOT NULL',
                              'freq' => 'VARCHAR(10) NOT NULL',
                              'band' => 'VARCHAR(10) NOT NULL',
                              'mode' => 'VARCHAR(10) NOT NULL',
                              'rst_sent' => 'VARCHAR(5)',
                              'rst_rcvd' => 'VARCHAR(5)',
                              'name' => 'VARCHAR(100)',
                              'qsl_sent' => 'TINYINT(1)',
                              'qsl_rcvd' => 'TINYINT(1)',
                              );
  $columns[$table] = array_keys($attribute[$table]);
  
  $table = "call_signs";
  $attribute[$table] = array('id' => 'INT(9) NOT NULL AUTO_INCREMENT',
                              'callsign' => 'VARCHAR( 10 ) NOT NULL',
                              'operator' => 'VARCHAR( 100 ) NOT NULL');                              
  $columns[$table] = array_keys($attribute[$table]);  
  
  $tables = array_keys($columns);
  foreach ($tables as $table){
    foreach ($columns[$table] as $column) {
      if(!$wpdb->query("Show columns from `".$wpdb->prefix.$table."` like '".$column."'")) {
        if($column == 'id') {
          $query = "CREATE TABLE ".$wpdb->prefix.$table." (id ".$attribute[$table]['id'].", UNIQUE KEY id (id));";
        } else {
          $query = "ALTER TABLE `".$wpdb->prefix.$table."` ADD `".$column."` ".$attribute[$table][$column].";"; 
        }
        $wpdb->query($query); 
      }
    }
  }
  
  return;
}
if ( function_exists('register_activation_hook') )
      register_activation_hook(__FILE__, 'wplog_viewer_install');

/*
function wplog_viewer_uninstall(){
    
}

if ( function_exists('register_uninstall_hook') )
      register_uninstall_hook(__FILE__, 'wplog_viewer_uninstall');
*/

?>
