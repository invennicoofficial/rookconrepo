<?php
/* Update Databases */
include ('database_connection.php');
error_reporting(0);

echo "Beginning Database Updates<br />\n";

//May 03, 2017 - Ticket #2602
echo "<br />Ticket #2602 - Update Contact Field Defaults<br />\n";

$tabs = ['Customers', 'Clients', 'Sales Leads'];

foreach ($tabs as $tabname) {
  echo "<br />$tabname<br />\n";
  if(!mysqli_query($dbc, "SELECT COUNT(*) FROM `field_config_contacts` WHERE `tile_name` = 'contacts' AND `tab` = '$tabname'")) {
    echo "Error: ".mysqli_error($dbc)."<br />\n";
  } else {
    $rowcount = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) rowcount FROM `field_config_contacts` WHERE `tile_name` = 'contacts' AND `tab` = '$tabname'"))['rowcount'];
    if ($rowcount == 0) {
      echo "Contacts Dashboard - ";
      if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`,`tab`,`subtab`,`accordion`,`contacts`,`order`,`contacts_dashboard`) VALUES ('contacts','$tabname','',NULL,NULL,NULL,'Category,Business,First Name,Last Name,Office Phone,Cell Phone,Email Address,Website,Position')")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
      } else {
        echo "Success<br />\n";
      }

      echo "Business Information - ";
      if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`,`tab`,`subtab`,`accordion`,`contacts`,`order`,`contacts_dashboard`) VALUES ('contacts','$tabname','','Business Information','Category,Business',1,NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
      } else {
        echo "Success<br />\n";
      }

      echo "Contact Information - ";
      if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`,`tab`,`subtab`,`accordion`,`contacts`,`order`,`contacts_dashboard`) VALUES ('contacts','$tabname','','Contact Information','Category,First Name,Last Name,Preferred Name,Role,Classification,Office Phone,Cell Phone,Home Phone,Fax,Email Address,Website,Referred By,Position',2,NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
      } else {
        echo "Success<br />\n";
      }

      echo "Contact Description - ";
      if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`,`tab`,`subtab`,`accordion`,`contacts`,`order`,`contacts_dashboard`) VALUES ('contacts','$tabname','','Contact Description','Category,Classification',3,NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
      } else {
        echo "Success<br />\n";
      }

      echo "Social Media Links - ";
      if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`,`tab`,`subtab`,`accordion`,`contacts`,`order`,`contacts_dashboard`) VALUES ('contacts','$tabname','','Social Media Links','Category,LinkedIn,Facebook,Twitter',4,NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
      } else {
        echo "Success<br />\n";
      }

      echo "Comments - ";
      if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`,`tab`,`subtab`,`accordion`,`contacts`,`order`,`contacts_dashboard`) VALUES ('contacts','$tabname','','Comments','Category,Comments',5,NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
      } else {
        echo "Success<br />\n";
      }

      echo "Status - ";
      if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`,`tab`,`subtab`,`accordion`,`contacts`,`order`,`contacts_dashboard`) VALUES ('contacts','$tabname','','Status','Category,Status',6,NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
      } else {
        echo "Success<br />\n";
      }
    } else {
      echo "$tabname already exists in database<br />\n";
    }
  }
}
echo "<br />Ticket #2602 - End<br />";


echo "Done Updating Databases\n";
?>
