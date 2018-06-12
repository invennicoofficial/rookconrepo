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

    $result = mysqli_query($dbc, "DELETE FROM contacts WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_a WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_b WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_c WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_d WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_e WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_f WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_g WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_h WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_i WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_j WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_k WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_l WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_m WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_n WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_o WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_p WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_q WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_r WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_s WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_t WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_u WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_v WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_w WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_x WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_y WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_fn_z WHERE contactid IN(11272,18316,18196)");

    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_a WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_b WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_c WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_d WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_e WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_f WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_g WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_h WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_i WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_j WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_k WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_l WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_m WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_n WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_o WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_p WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_q WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_r WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_s WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_t WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_u WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_v WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_w WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_x WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_y WHERE contactid IN(11272,18316,18196)");
    $result = mysqli_query($dbc, "DELETE FROM contacts_ln_z WHERE contactid IN(11272,18316,18196)");


    ?>

	</div>
</div>


<?php include ('../footer.php'); ?>