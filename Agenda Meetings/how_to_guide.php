<?php
/*
Inventory Listing
*/
include ('../include.php');

?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('agenda_meeting');
?>
<div class="container">
	<div class="row">

		<div>
			<div class="col-sm-10">
				<h1>How To Guide</h1>
			</div>
			<div class="clearfix"></div>
        </div>

        <div class="gap-top tab-container mobile-100-container"><?php
            if ( check_subtab_persmission( $dbc, 'agenda_meeting', ROLE, 'how_to_guide' ) !== false ) { ?>
                <a href="how_to_guide.php"><button type="button" class="btn brand-btn mobile-block mobile-100 tab active_tab">How To Guide</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100 tab">How To Guide</button><?php
            }
            
            if ( check_subtab_persmission( $dbc, 'agenda_meeting', ROLE, 'agenda' ) !== false ) { ?>
                <a href="agenda.php"><button type="button" class="btn brand-btn mobile-block mobile-100 tab">Agendas</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100 tab">Agendas</button><?php
            }
            
            if ( check_subtab_persmission( $dbc, 'agenda_meeting', ROLE, 'meeting' ) !== false ) { ?>
                <a href="meeting.php"><button type="button" class="btn brand-btn mobile-block mobile-100 tab">Meetings</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100 tab">Meetings</button><?php
            } ?>
		</div>

		<h3>Coming Soon!</h3>
	</div>
</div>

<?php include ('../footer.php'); ?>