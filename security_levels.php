<?php
/*
LEvels
*/
include ('include.php');
?>
<script type="text/javascript">
    function securityConfig(sel) {
        var type = sel.type;
        var name = sel.name;
        var tile_value = sel.value;
        var final_value = '*';
        if($("#"+name+"_turn_on").is(":checked")) {
            final_value += 'turn_on*';
        }
        if($("#"+name+"_turn_off").is(":checked")) {
            final_value += 'turn_off*';
        }
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "ajax_all.php?fill=security_level&name="+name+"&value="+final_value,
			dataType: "html",   //expect html to be returned
			success: function(response){
                location.reload();
			}
		});
    }
</script>
</head>
<body>
<?php include_once ('navigation.php');
checkAuthorised();
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <?php include('Settings/settings_navigation.php'); ?>
        <br><br>

        <?php
        $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM security_level WHERE securitylevelid=1"));
        $admin = $get_config['admin'];
		$therapist = $get_config['therapist'];
        $executive = $get_config['executive'];
        $opsmanager = $get_config['opsmanager'];
		$genmanager = $get_config['genmanager'];
        $manfmanager = $get_config['manfmanager'];
        $mrkmanager = $get_config['mrkmanager'];
        $salesmanager = $get_config['salesmanager'];
        $hrmanager = $get_config['hrmanager'];
        $accmanager = $get_config['accmanager'];
        $invmanager = $get_config['invmanager'];
        $teamlead = $get_config['teamlead'];
        $operations = $get_config['operations'];
        $marketing = $get_config['marketing'];
        $sales = $get_config['sales'];
        $humanres = $get_config['humanres'];
        $accounting = $get_config['accounting'];
        $safety = $get_config['safety'];
        $fieldops = $get_config['fieldops'];
        $assembler = $get_config['assembler'];
        $contractor = $get_config['contractor'];
        $teammember = $get_config['teammember'];
        $staff = $get_config['staff'];
        $customers = $get_config['customers'];

	  $chairman = $get_config['chairman'];
	  $president = $get_config['president'];
	  $vicepres = $get_config['vicepres'];
	  $coo = $get_config['coo'];
	  $ceo = $get_config['ceo'];
	  $ced = $get_config['ced'];
	  $cfo = $get_config['cfo'];
	  $cfd = $get_config['cfd'];
	  $cod = $get_config['cod'];
	  $exdirect = $get_config['exdirect'];
	  $findirect = $get_config['findirect'];
	  $salesmarketingdirect = $get_config['salesmarketingdirect'];
	  $salesdirector = $get_config['salesdirector'];
	  $marketingdirector = $get_config['marketingdirector'];
	  $commsalesdirector = $get_config['commsalesdirector'];
	  $vpcorpdev = $get_config['vpcorpdev'];
	  $vpsales = $get_config['vpsales'];
	  $operationslead = $get_config['operationslead'];
	  $suppchainlogist = $get_config['suppchainlogist'];
	  $fieldopmanager = $get_config['fieldopmanager'];
	  $regionalmanager = $get_config['regionalmanager'];
	  $officemanager = $get_config['officemanager'];
	  $businessdevmanager = $get_config['businessdevmanager'];
	  $controller = $get_config['controller'];
	  $businessdevcoo = $get_config['businessdevcoo'];
	  $opcoord = $get_config['opcoord'];
	  $safetysup = $get_config['safetysup'];
	  $fluidhaulingman = $get_config['fluidhaulingman'];
	  $teamcolead = $get_config['teamcolead'];
	  $execassist = $get_config['execassist'];
	  $assist = $get_config['assist'];
	  $fieldsup = $get_config['fieldsup'];
	  $waterspec = $get_config['waterspec'];
      $calllog = $get_config['calllog'];
	  $budget = $get_config['budget'];
	  $gao = $get_config['gao'];
	  $opconsult = $get_config['opconsult'];
	  $manager = $get_config['manager'];
	  $advocate = $get_config['advocate'];
	  $supporter = $get_config['supporter'];
	  $client = $get_config['client'];
	  $prospect = $get_config['prospect'];
	  $lead = $get_config['lead'];
	  $foreman=$get_config['foreman'];
	  $shopforeman=$get_config['shopforeman'];
	  $shopworker=$get_config['shopworker'];
	  $daypass=$get_config['daypass'];
        $office_admin = $get_config['office_admin'];
		$executive_front_staff = = $get_config['executive_front_staff'];

        $ad = explode('*#*', $admin);
        $ex = explode('*#*', $executive);
		$gnmn = explode('*#*', $genmanager);
        $opsm = explode('*#*', $opsmanager);
        $mnfm = explode('*#*', $manfmanager);
        $mrm = explode('*#*', $mrkmanager);
        $samm = explode('*#*', $salesmanager);
        $hrm = explode('*#*', $hrmanager);
        $acm = explode('*#*', $accmanager);
        $inm = explode('*#*', $invmanager);
        $tel = explode('*#*', $teamlead);
        $op = explode('*#*', $operations);
        $ma = explode('*#*', $marketing);
        $sa = explode('*#*', $sales);
        $hu = explode('*#*', $humanres);
        $ac = explode('*#*', $accounting);
        $saf = explode('*#*', $safety);
        $fi = explode('*#*', $fieldops);
        $ass = explode('*#*', $assembler);
        $co = explode('*#*', $contractor);
        $te = explode('*#*', $teammember);
        $st = explode('*#*', $staff);
        $cu = explode('*#*', $customers);

	  $cm = explode('*#*', $chairman);
	  $pres = explode('*#*', $president);
	  $vicep = explode('*#*', $vicepres);
	  $chiefoo = explode('*#*', $coo);
	  $chiefeo = explode('*#*', $ceo);
	  $ceedd = explode('*#*', $ced);
	  $chieffo = explode('*#*', $cfo);
	  $cfdd = explode('*#*', $cfd);
	  $callofduty = explode('*#*', $cod);
	  $exd = explode('*#*', $exdirect);
	  $fd = explode('*#*', $findirect);
	  $smd = explode('*#*', $salesmarketingdirect);
	  $sd = explode('*#*', $salesdirector);
	  $md = explode('*#*', $marketingdirector);
	  $csd = explode('*#*', $commsalesdirector);
	  $vpcd = explode('*#*', $vpcorpdev);
	  $vps = explode('*#*', $vpsales);
	  $opl = explode('*#*', $operationslead);
	  $scl = explode('*#*', $suppchainlogist);
	  $fom = explode('*#*', $fieldopmanager);
	  $rm = explode('*#*', $regionalmanager);
	  $om = explode('*#*', $officemanager);
	  $bdm = explode('*#*', $businessdevmanager);
	  $contro = explode('*#*', $controller);
	  $bdvc = explode('*#*', $businessdevcoo);
	  $opcoo = explode('*#*', $opcoord);
	  $sasu = explode('*#*', $safetysup);
	  $fluham = explode('*#*', $fluidhaulingman);
	  $tecole = explode('*#*', $teamcolead);
	  $execas = explode('*#*', $execassist);
	  $assi = explode('*#*', $assist);
	  $fsup = explode('*#*', $fieldsup);
	  $wasp = explode('*#*', $waterspec);
      $wasp = explode('*#*', $waterspec);
	  $opcon = explode('*#*', $opconsult);
	  $manng = explode('*#*', $manager);
	  $advc = explode('*#*', $advocate);
	  $suppo = explode('*#*', $supporter);
	  $clien = explode('*#*', $client);
	  $prospy = explode('*#*', $prospect);
	  $led = explode('*#*', $lead);
	  $foman = explode('*#*', $foreman);
	  $shfoman = explode('*#*', $shopforeman);
	  $shwork = explode('*#*', $shopworker);
	  $dpass = explode('*#*', $daypass);
      $offad = explode('*#*', $office_admin);
	  $cal = explode('*#*', $calllog);
	  $bud = explode('*#*', $budget);
	  $gao = explode('*#*', $gao);
	  $thera = explode('*#*', $therapist);
	  $efs = explode('*#*', $executive_front_staff);

        ?>

        <br><br>
        <table class='table table-bordered'>
            <tr class='hidden-sm hidden-xs'>
                <th>Activate Security Levels For Configuration</th>
                <th>
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Activating this security level allows you to configure and assign to staff, clients and contractors you wish to provide access to your software."><img src="img/info.png" width="20"></a>
                </span>
                Turn On</th>
                <th>
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Security levels turned off will close and remove access for users set at this security level."><img src="img/info.png" width="20"></a>
                </span>
                Turn Off</th>
                <th>Date Activated</th>
            </tr>

            <tr>
                <td data-title="Comment">Admin</td>
                <?php echo secutiry_level_function('admin', $admin, $ad); ?>
            </tr>
			<tr>
                <td data-title="Comment">Therapist</td>
                <?php echo secutiry_level_function('admin', $therapist, $thera); ?>
            </tr>
			<tr>
                <td data-title="Comment">Accounting</td>
                <?php echo secutiry_level_function('accounting', $accounting, $ac); ?>
            </tr>
			<tr>
                <td data-title="Comment">Accounting Manager</td>
                <?php echo secutiry_level_function('accmanager', $accmanager, $acm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Advocate</td>
                <?php echo secutiry_level_function('advocate', $advocate, $advc); ?>
            </tr>
			<tr>
                <td data-title="Comment">Assembler</td>
                <?php echo secutiry_level_function('assembler', $assembler, $ass); ?>
            </tr>
			<tr>
                <td data-title="Comment">Assistant</td>
                <?php echo secutiry_level_function('assist', $assist, $assi); ?>
            </tr>
			<tr>
                <td data-title="Comment">Business Development Manager</td>
                <?php echo secutiry_level_function('businessdevmanager', $businessdevmanager, $bdm); ?>
            </tr>

			<tr>
                <td data-title="Comment">Business Development Coordinator</td>
                <?php echo secutiry_level_function('businessdevcoo', $businessdevcoo, $bdvc); ?>
            </tr>
			 <tr>
                <td data-title="Comment">Contractor</td>
                <?php echo secutiry_level_function('contractor', $contractor, $co); ?>
            </tr>
			<tr>
                <td data-title="Comment">Chairman</td>
                <?php echo secutiry_level_function('chairman', $chairman, $cm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Chief Executive Director</td>
                <?php echo secutiry_level_function('ced', $ced, $ceedd); ?>
            </tr>
			<tr>
                <td data-title="Comment">Chief Executive Officer</td>
                <?php echo secutiry_level_function('ceo', $ceo, $chiefeo); ?>
            </tr>
			<tr>
                <td data-title="Comment">Chief Financial Director</td>
                <?php echo secutiry_level_function('cfd', $cfd, $cfdd); ?>
            </tr>
			<tr>
                <td data-title="Comment">Chief Financial Officer</td>
                <?php echo secutiry_level_function('cfo', $cfo, $chieffo); ?>
            </tr>
			<tr>
                <td data-title="Comment">Chief Operating Officer</td>
                <?php echo secutiry_level_function('coo', $coo, $chiefoo); ?>
            </tr>
			<tr>
                <td data-title="Comment">Chief Operations Director</td>
                <?php echo secutiry_level_function('cod', $cod, $callofduty); ?>
            </tr>
			<tr>
                <td data-title="Comment">Client</td>
                <?php echo secutiry_level_function('client', $client, $clien); ?>
            </tr>
			<tr>
                <td data-title="Comment">Commercial Sales Director</td>
                <?php echo secutiry_level_function('commsalesdirector', $commsalesdirector, $csd); ?>
            </tr>
			<tr>
                <td data-title="Comment">Customers</td>
                <?php echo secutiry_level_function('customers', $customers, $cu); ?>
            </tr>
			<tr>
                <td data-title="Comment">Controller</td>
                <?php echo secutiry_level_function('controller', $controller, $contro); ?>
            </tr>
			<tr>
                <td data-title="Comment">Day Pass</td>
                <?php echo secutiry_level_function('daypass', $daypass, $dpass); ?>
            </tr>
            <tr>
                <td data-title="Comment">Executive</td>
                <?php echo secutiry_level_function('executive', $executive, $ex); ?>
            </tr>
			<tr>
                <td data-title="Comment">Executive Assistant</td>
                <?php echo secutiry_level_function('execassist', $execassist, $execas); ?>
            </tr>
			<tr>
                <td data-title="Comment">Executive Director</td>
                <?php echo secutiry_level_function('exdirect', $exdirect, $exd); ?>
            </tr>
			<tr>
                <td data-title="Comment">Field Operations</td>
                <?php echo secutiry_level_function('fieldops', $fieldops, $fi); ?>
            </tr>
			<tr>
                <td data-title="Comment">Field Operations Manager</td>
                <?php echo secutiry_level_function('fieldopmanager', $fieldopmanager, $fom); ?>
            </tr>
			<tr>
                <td data-title="Comment">Field Supervisor</td>
                <?php echo secutiry_level_function('fieldsup', $fieldsup, $fsup); ?>
            </tr>
			<tr>
                <td data-title="Comment">Financial Director</td>
                <?php echo secutiry_level_function('findirect', $findirect, $fd); ?>
            </tr>
			<tr>
                <td data-title="Comment">Fluid Hauling Manager</td>
                <?php echo secutiry_level_function('fluidhaulingman', $fluidhaulingman, $fluham); ?>
            </tr>
			<tr>
                <td data-title="Comment">Foreman</td>
                <?php echo secutiry_level_function('foreman', $foreman, $foman); ?>
            </tr>
			<tr>
                <td data-title="Comment">General Manager</td>
                <?php echo secutiry_level_function('genmanager', $genmanager, $gnmn); ?>
            </tr>
			<tr>
                <td data-title="Comment">HR Manager</td>
                <?php echo secutiry_level_function('hrmanager', $hrmanager, $hrm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Human Resources</td>
                <?php echo secutiry_level_function('humanres', $humanres, $hu); ?>
            </tr>
			<tr>
                <td data-title="Comment">Inventory Manager</td>
                <?php echo secutiry_level_function('invmanager', $invmanager, $inm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Lead</td>
                <?php echo secutiry_level_function('lead', $lead, $led); ?>
            </tr>
			<tr>
                <td data-title="Comment">Manufacturing Manager</td>
                <?php echo secutiry_level_function('manfmanager', $manfmanager, $mnfm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Managers</td>
                <?php echo secutiry_level_function('manager', $manager, $manng); ?>
            </tr>
			<tr>
                <td data-title="Comment">Marketing</td>
                <?php echo secutiry_level_function('marketing', $marketing, $ma); ?>
            </tr>
			<tr>
                <td data-title="Comment">Marketing Director</td>
                <?php echo secutiry_level_function('marketingdirector', $marketingdirector, $md); ?>
            </tr>
			<tr>
                <td data-title="Comment">Marketing Manager</td>
                <?php echo secutiry_level_function('mrkmanager', $mrkmanager, $mrm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Office Admin</td>
                <?php echo secutiry_level_function('office_admin', $office_admin, $offad); ?>
            </tr>
			<tr>
                <td data-title="Comment">Office Manager</td>
                <?php echo secutiry_level_function('officemanager', $officemanager, $om); ?>
            </tr>
			<tr>
                <td data-title="Comment">Operations</td>
                <?php echo secutiry_level_function('operations', $operations, $op); ?>
            </tr>
            <tr>
                <td data-title="Comment">Operations Manager</td>
                <?php echo secutiry_level_function('opsmanager', $opsmanager, $opsm); ?>
            </tr>
            <tr>
                <td data-title="Comment">Operations Consultant</td>
                <?php echo secutiry_level_function('opconsult', $opconsult, $opcon); ?>
            </tr>
			<tr>
                <td data-title="Comment">Operations Lead</td>
                <?php echo secutiry_level_function('operationslead', $operationslead, $opl); ?>
            </tr>
			<tr>
                <td data-title="Comment">Opertions Coordinator</td>
                <?php echo secutiry_level_function('opcoord', $opcoord, $opcoo); ?>
            </tr>
			<tr>
                <td data-title="Comment">President</td>
                <?php echo secutiry_level_function('president', $president, $pres); ?>
            </tr>
			<tr>
                <td data-title="Comment">Prospect</td>
                <?php echo secutiry_level_function('prospect', $prospect, $prospy); ?>
            </tr>
			<tr>
                <td data-title="Comment">Regional Manager</td>
                <?php echo secutiry_level_function('regionalmanager', $regionalmanager, $rm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Sales</td>
                <?php echo secutiry_level_function('sales', $sales, $sa); ?>
            </tr>
			<tr>
                <td data-title="Comment">Safety</td>
                <?php echo secutiry_level_function('safety', $safety, $saf); ?>
            </tr>
			<tr>
                <td data-title="Comment">Safety Supervisor</td>
                <?php echo secutiry_level_function('safetysup', $safetysup, $sasu); ?>
            </tr>
			<tr>
                <td data-title="Comment">Sales & Marketing Director</td>
                <?php echo secutiry_level_function('salesmarketingdirect', $salesmarketingdirect, $smd); ?>
            </tr>
			<tr>
                <td data-title="Comment">Sales Director</td>
                <?php echo secutiry_level_function('salesdirector', $salesdirector, $sd); ?>
            </tr>
			 <tr>
                <td data-title="Comment">Sales Manager</td>
                <?php echo secutiry_level_function('salesmanager', $salesmanager, $samm); ?>
            </tr>
			<tr>
                <td data-title="Comment">Shop Foreman</td>
                <?php echo secutiry_level_function('shopforeman', $shopforeman, $shfoman); ?>
            </tr>
			<tr>
                <td data-title="Comment">Shop Worker</td>
                <?php echo secutiry_level_function('shopworker', $shopworker, $shwork); ?>
            </tr>
			<tr>
                <td data-title="Comment">Staff</td>
                <?php echo secutiry_level_function('staff', $staff, $st); ?>
            </tr>
			<tr>
                <td data-title="Comment">Supply Chain & Logistics</td>
                <?php echo secutiry_level_function('suppchainlogist', $suppchainlogist, $scl); ?>
            </tr>
			<tr>
                <td data-title="Comment">Supporter</td>
                <?php echo secutiry_level_function('supporter', $supporter, $suppo); ?>
            </tr>
			<tr>
                <td data-title="Comment">Team Co-Lead</td>
                <?php echo secutiry_level_function('teamcolead', $teamcolead, $tecole); ?>
            </tr>
            <tr>
                <td data-title="Comment">Team Lead</td>
                <?php echo secutiry_level_function('teamlead', $teamlead, $tel); ?>
            </tr>

            <tr>
                <td data-title="Comment">Team Member</td>
                <?php echo secutiry_level_function('teammember', $teammember, $te); ?>
            </tr>
			<tr>
                <td data-title="Comment">Vice-President</td>
                <?php echo secutiry_level_function('vicepres', $vicepres, $vicep); ?>
            </tr>
			<tr>
                <td data-title="Comment">VP Corporate Development</td>
                <?php echo secutiry_level_function('vpcorpdev', $vpcorpdev, $vpcd); ?>
            </tr>
			<tr>
                <td data-title="Comment">VP Sales</td>
                <?php echo secutiry_level_function('vpsales', $vpsales, $vps); ?>
            </tr>
			<tr>
                <td data-title="Comment">Water Specialist</td>
                <?php echo secutiry_level_function('waterspec', $waterspec, $wasp); ?>
            </tr>
            <tr>
                <td data-title="Comment">Cold Call</td>
                <?php echo secutiry_level_function('calllog', $calllog, $cal); ?>
            </tr>
			<tr>
                <td data-title="Comment">Budget</td>
                <?php echo secutiry_level_function('budget', $budget, $bud); ?>
            </tr>
			<tr>
                <td data-title="Comment">Goals & Objectives</td>
                <?php echo secutiry_level_function('gao', $gao, $gao); ?>
            </tr>
			<tr>
                <td data-title="Comment">Executive Front Staff</td>
                <?php echo secutiry_level_function('gao', $executive_front_staff, $efs); ?>
            </tr>
        </table>

        <?php
        function secutiry_level_function($field, $value, $who_when) { ?>
            <td data-title="Unit Number"><input type="radio" <?php if (strpos($value, '*turn_on*') !== FALSE) {
                echo " checked"; } ?> onchange="securityConfig(this)" name="<?php echo $field;?>" value="turn_on" id="<?php echo $field;?>_turn_on" style="height:20px;width:20px;">
            </td>
            <td data-title="Unit Number"><input type="radio" <?php if (strpos($value, '*turn_off*') !== FALSE) {
                echo " checked"; } ?> onchange="securityConfig(this)" name="<?php echo $field;?>" value="turn_off" id="<?php echo $field;?>_turn_off" style="height:20px;width:20px;">
            </td>
            <td data-title="Unit Number"><?php if(!empty($who_when[1])) { echo $who_when[1]; } else { echo '-'; } ?></td>
        <?php } ?>
        </div>
    </div>
</div>
<?php include ('footer.php'); ?>
