<?php
$get_field_config_custom = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT custom FROM field_config"));
$field_config_custom = ','.$get_field_config_custom['custom'].',';

