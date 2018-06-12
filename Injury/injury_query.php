<?php
/* Update Databases */
include ('../database_connection.php');
error_reporting(0);

mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'AHS' WHERE injury_type = 'AHS Physical Therapy'");
mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'MVA/MVC - Out of Protocol' WHERE injury_type = 'MVA Massage Therapy 60 Minutes -  Out Protocol'");
mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'MVA/MVC - In Protocol' WHERE injury_type = 'MVA Massage Therapy 60 Minutes - In  Protocol'");
mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'MVA/MVC - In Protocol' WHERE injury_type = 'MVA Physical Therapy - In Protocol'");
mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'MVA/MVC - Out of Protocol' WHERE injury_type = 'MVA Physical Therapy - Out of Protocol'");
mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'Private Massage' WHERE injury_type = 'Private Massage Therapy'");
mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'Private Physio' WHERE injury_type = 'Private Physical Therapy'");
mysqli_query($dbc,"UPDATE patient_injury SET injury_type = 'WCB' WHERE injury_type = 'WCB Physical Therapy'");

echo 'Done';