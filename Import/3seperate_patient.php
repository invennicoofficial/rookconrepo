<?php
/*
Dashboard
*/
include ('../include.php');

?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">

    <?php

    mysqli_query($dbc,"ALTER TABLE `contacts_fn_a` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_b` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_c` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_d` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_e` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_f` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_g` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_h` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_i` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_j` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_k` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_l` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_m` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_n` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_o` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_p` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_q` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_r` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_s` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_t` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_u` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_v` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_w` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_x` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_y` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_fn_z` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");


    mysqli_query($dbc,"ALTER TABLE `contacts_ln_a` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_b` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_c` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_d` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_e` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_f` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_g` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_h` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_i` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_j` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_k` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_l` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_m` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_n` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_o` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_p` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_q` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_r` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_s` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_t` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_u` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_v` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_w` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_x` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_y` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");
    mysqli_query($dbc,"ALTER TABLE `contacts_ln_z` ADD `patientid` INT(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`patientid`)");

    echo 'Done';

    ?>

	</div>
</div>


<?php include ('../footer.php'); ?>