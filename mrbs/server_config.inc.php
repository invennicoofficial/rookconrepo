<?php
// $Id$

/**************************************************************************
 *   MRBS Configuration File
 *   Configure this file for your site.
 *   You shouldn't have to modify anything outside this file.
 *
 *   This file has already been populated with the minimum set of configuration
 *   variables that you will need to change to get your system up and running.
 *   If you want to change any of the other settings in systemdefaults.inc.php
 *   or areadefaults.inc.php, then copy the relevant lines into this file
 *   and edit them here.   This file will override the default settings and
 *   when you upgrade to a new version of MRBS the config file is preserved.
 **************************************************************************/

/**********
 * Timezone
 **********/

// The timezone your meeting rooms run in. It is especially important
// to set this if you're using PHP 5 on Linux. In this configuration
// if you don't, meetings in a different DST than you are currently
// in are offset by the DST offset incorrectly.
//
// Note that timezones can be set on a per-area basis, so strictly speaking this
// setting should be in areadefaults.inc.php, but as it is so important to set
// the right timezone it is included here.
//
// When upgrading an existing installation, this should be set to the
// timezone the web server runs in.  See the INSTALL document for more information.
//
// A list of valid timezones can be found at http://php.net/manual/timezones.php
// The following line must be uncommented by removing the '//' at the beginning
$timezone = "Europe/London";


/*******************
 * Database settings
 ******************/
// Which database system: "pgsql"=PostgreSQL, "mysqli"=MySQL
$dbsys = "mysqli";
// Hostname of database server. For pgsql, can use "" instead of localhost
// to use Unix Domain Sockets instead of TCP/IP. For mysql/mysqli "localhost"
// tells the system to use Unix Domain Sockets, and $db_port will be ignored;
// if you want to force TCP connection you can use "127.0.0.1".
$db_host = "mysql.freshfocussoftware.com";
// If you need to use a non standard port for the database connection you
// can uncomment the following line and specify the port number
// $db_port = 1234;
// Database name:
$db_database = "5th_ffm_db";
// Schema name.  This only applies to PostgreSQL and is only necessary if you have more
// than one schema in your database and also you are using the same MRBS table names in
// multiple schemas.
//$db_schema = "public";
// Database login user name:
$db_login = "freshfocus_db";
// Database login password:
$db_password = "R0bot587tw3ak";
// Prefix for table names.  This will allow multiple installations where only
// one database is available
$db_tbl_prefix = "mrbs_";
// Uncomment this to NOT use PHP persistent (pooled) database connections:
// $db_nopersist = 1;


/* Add lines from systemdefaults.inc.php and areadefaults.inc.php below here
   to change the default configuration. Do _NOT_ modify systemdefaults.inc.php
   or areadefaults.inc.php.  */


/* Custom Code */
// Add color Coded
$vocab_override["en"]["type.W"] =     "Weekend";
$vocab_override["en"]["type.H"] =     "Holiday";
$booking_types[] = "W";
$booking_types[] = "H";

// Add Field Patient
$vocab['entry.patient'] = "Patient";

$dbc = mysqli_connect($db_host, $db_login, $db_password, $db_database);
$query = "SELECT patientid, first_name, last_name FROM patients";
$result = mysqli_query($dbc, $query);
$data_array = array(''  => 'Please select');

while ($row = mysqli_fetch_assoc($result)) {
    $data_array[$row['patientid']] = $row['first_name'].' '.$row['last_name'];
}
$select_options['entry.patient'] = $data_array;

// Add Field Patient Status
$vocab['entry.patientstatus'] = "Patient Status";

$select_options['entry.patientstatus'] = array(''  => 'Please select',
                                          'Booking' => 'Booking',
                                          'Need to Review' => 'Need to Review',
                                          'Done' => 'Done');


$page_level['check_slot_ajax.php']       = 2;
$page_level['day.php']                   = 2;
$page_level['help.php']                  = 2;
$page_level['month.php']                 = 2;
$page_level['report.php']                = 2;
$page_level['search.php']                = 2;
$page_level['view_entry.php']            = 2;
$page_level['week.php']                  = 2;


$page_level['admin.php']                 = 2;  // Ordinary users can view room details
$page_level['approve_entry_handler.php'] = 2;  // Ordinary users are allowed to remind admins
$page_level['del_entry.php']             = 2;
$page_level['edit_area_room.php']        = 2;  // Ordinary users can view room details
$page_level['edit_entry.php']            = 2;
$page_level['edit_entry_handler.php']    = 2;
$page_level['edit_users.php']            = 2;  // Ordinary users can edit their own details
$page_level['pending.php']               = 2;  // Ordinary users can view their own entries
