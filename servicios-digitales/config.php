<?php
global $wpdb;
define('SDDIRPATH',dirname(__FILE__)."/");
define('SDDIRRELATIVEPATH',"/wp-content/plugins/servicios-digitales/");
define('SDDIRCALLBACK',get_option("siteurl").SDDIRRELATIVEPATH);
define('SDDIRDBTABLE',$wpdb->prefix."sd_listings");
define('SDDIRCATTABLE', $wpdb->prefix."sd_categories");
define('SDDIRUSOTABLE', $wpdb->prefix."sd_uso");
define('PERPAGE',15);
//Variables
$servdig_version_var = "0.8.6.1 Beta";
//Require Scripts
require_once(SDDIRPATH.'functions.php'); //Load Biz-Directory Functions Library File
