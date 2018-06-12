<?php
	/*
	 * iFrame content to select Contact category
	 */
	include ('../include.php');
	checkAuthorised('intake');
	error_reporting(0);
?>

	<script type="text/javascript">
		$(document).ready(function() {
			initSelectOnChange();
		});
		function selectCategory() {
			var category	= $('#contact_category').val();
			var action		= $('#action').val();
			var intakeid	= $('#intakeid').val();
			
			if (action=='create') {
				window.top.location.href = "<?= WEBSITE_URL; ?>/Contacts/add_contacts.php?category="+category+"&intakeid="+intakeid;
			} else if (action=='assign') {
				$.ajax({
					type:		"GET",
					url:		"intake_ajax_all.php?fill=getContactsList&category="+category,
					dataType:	"html",
					success:	function(response) {
						destroyInputs('.intake_block');
						$("#contact_list").html(response);
						$("#contact_list").trigger("change.select2");
						initInputs('.intake_block');
						initSelectOnChange();
					}
				});
			} else if(action=='project') {
				window.top.location.href = "<?= WEBSITE_URL; ?>/Contacts/add_contacts.php?category="+category+"&project_type=Client&intakeid="+intakeid;
			}
		}
		
		function selectContact() {
			var category	= $('#contact_category').val();
			var contactid	= $('#contact_list').val();
			var intakeid	= $('#intakeid').val();
			
			window.top.location.href = "<?= WEBSITE_URL; ?>/Contacts/add_contacts.php?category="+category+"&contactid="+contactid+"&intakeid="+intakeid;
		}

		function initSelectOnChange() {
			$('select[name="contact_category"]').on('change', function() { selectCategory(this); });
			$('select[name="contact_list"]').on('change', function() { selectContact(this); });
		}
	</script>
</head>

<body>
	<div class="container intake_block">
		<div class="row"><?php
			$subtitle	  = $_GET['subtitle'];
			$action		  = $_GET['action'];
			$intakeid	  = $_GET['intakeid'];
            $contact_type = $_GET['contact_type']; ?>
			
			<input type="hidden" name="action" id="action" value="<?= $action; ?>" />
			<input type="hidden" name="intakeid" id="intakeid" value="<?= $intakeid; ?>" /><?php
			
			echo '<h1>' . $subtitle . '</h1>';
			
			if ( $action=='create' || $action=='assign' || $action=='project' ) {
				if ( $contact_type == 'Patient' ) {
                    $cat_text = 'Category the new patient will be added to:';
                } else {
                    $cat_text = 'Please select a Contact category:';
                }
                echo '<p class="gap-left">'. $cat_text .'</p>';
				
				$tabs		= str_replace(',,', ',', str_replace('Staff', '', get_config($dbc, 'contacts_tabs')));
				$each_tab	= explode(',', $tabs); ?>
				
				<div class="gap-left"><?php
					if ( $contact_type == 'Patient' ) { ?>
                        <select name="contact_category" id="contact_category" data-placeholder="Select a Contact category" width="380" class="chosen-select-deselect form-control">
                            <option value=""></option><?php
                            foreach ($each_tab as $cat_tab) {
                                $selected = ( $cat_tab=='Patient' || $cat_tab=='Patients' ) ? 'selected="selected"' : '';
                                echo '<option value="' . $cat_tab . '" ' . $selected . '>' . $cat_tab . '</option>';
                            } ?>
                        </select><?php
                        
                        echo '<script>selectCategory();</script>';
                    
                    } else { ?>
                        <select name="contact_category" id="contact_category" data-placeholder="Select a Contact category" width="380" class="chosen-select-deselect form-control">
                            <option value=""></option><?php
                            foreach ($each_tab as $cat_tab) {
                                echo '<option value="' . $cat_tab . '" ' . $selected . '>' . $cat_tab . '</option>';
                            } ?>
                        </select><?php
                    }
					
					/* Select the Contact */
					if ( $action == 'assign' ) { ?>
						<br /><br />
						<p>Select the <?= $contact_type; ?> you want this Intake Form Submission to assign to:</p>
						<select name="contact_list" id="contact_list" data-placeholder="Select a <?= $contact_type; ?>" width="380" class="chosen-select-deselect form-control">
						</select><?php
					} ?>
					
				</div><?php
			} ?>
		</div><!-- .row -->
	</div><!-- .container -->
	
<?php include ('../footer.php'); ?>