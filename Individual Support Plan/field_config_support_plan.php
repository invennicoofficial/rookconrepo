<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('individual_support_plan');
error_reporting(0);

if (isset($_POST['submit'])) {
    $individual_support_plan = implode(',',$_POST['isp_fields']).',CONFIG_UPDATED';

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET individual_support_plan = '$individual_support_plan' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`individual_support_plan`) VALUES ('$individual_support_plan')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_support_plan.php"); </script>';

}

$isp_fields = [
    'Service Individual' => ['Service Individual Contact','Service Individual Gender','Service Individual School','Service Individual Grade/Class','Service Individual Diagnosis','Service Individual Date of Birth','Service Individual Other Supports'],
    'Day Program Support Team' => ['Day Program Support Team Primary Contact','Day Program Support Team Lead','Day Program Support Team Key Supports','Day Program Support Team Coordinator','Day Program Support Team Speech-Language Pathologist','Day Program Support Team Occupational Therapist','Day Program Support Team Provisional Psychologist','Day Program Support Team Physiotherapist','Day Program Support Team Aides','Day Program Support Team FSCD Worker'],
    'Residential Support Team' => ['Residential Support Team Primary Contact','Residential Support Team Lead','Residential Support Team Key Supports'],
    'Guardian' => ['Guardian Primary Contact','Guardian Secondary Contact','Guardian Alternates'],
    'Family Support Goals' => ['Family Support Goals Goal 1','Family Support Goals Goal 2','Family Support Goals Goal 3','Family Support Goals Goal 4','Family Support Goals Long Term Goal 1'],
    'Emergency Contacts' => [],
    'Parent Rating' => ['Parent Rating Note','Parent Rating Behaviour','Parent Rating Communication & Social Skills','Parent Rating Physical Abilities','Parent Rating Cognitive Abilities','Parent Rating Safety'],
    'Dates & Timelines' => ['ISP Start Date','ISP Review Date','ISP End Date'],
    'ISP Details' => ['Quality of Life Outcomes','Goals','Assessed Service Needs','Support Strategies','Support Objectives','SIS Activity Areas','Who is Responsible','Updates'],
    'ISP Notes' => [],
    'Signatures' => ['Signatures Parents','Signatures Coordinator','Signatures Speech-Language','Signatures Occupational Therapist','Signatures Provisional Psychologist','Signatures Physiotherapist','Signatures Aides']
]
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Individual Support Plan</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="individual_support_plan.php" class="btn config-btn">Back to Dashboard</a></div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields to Display<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT individual_support_plan FROM field_config"));
                $value_config = ','.$get_field_config['individual_support_plan'].',';

                foreach ($isp_fields as $accordion => $fields) { ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?= $accordion ?>:</label>
                        <div class="col-sm-8">
                            <label class="form-checkbox"><input type="checkbox" name="isp_fields[]" value="<?= $accordion ?>" <?= strpos($value_config, ','.$accordion.',') !== FALSE || $accordion == 'Service Individual' ? 'checked' : '' ?> <?= $accordion == 'Service Individual' ? 'disabled' : '' ?>> Enable</label>
                                <?php if(!empty($fields)) { ?>
                                    <div class="block-group">
                                        <?php foreach($fields as $field) { ?>
                                            <label class="form-checkbox"><input type="checkbox" name="isp_fields[]" value="<?= $field ?>" <?= strpos($value_config, ','.$field.',') !== FALSE || $field == 'Service Individual Contact' ? 'checked' : '' ?> <?= $field == 'Service Individual Contact' ? 'disabled' : '' ?>> <?= str_replace($accordion.' ','',$field) ?></label>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </label>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-6"><a href="individual_support_plan.php" class="btn config-btn btn-lg">Back</a></div>
	<div class="col-sm-6"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button></div>
	<div class="clearfix"></div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>