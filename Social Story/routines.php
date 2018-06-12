<?php
include('../include.php');
error_reporting(0);
?>
</head>
<body>
<?php 
include_once ('../navigation.php');
checkAuthorised('social_story');
include 'config.php';

$value = $config['settings']['Choose Fields for Routines Dashboard'];

?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <h1 class="">Social Stories: Routines Dashboard
        <?php
        if(config_visible_function_social($dbc)) {
            echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

        <form id="form1" name="form1" method="get" enctype="multipart/form-data" class="form-horizontal" role="form">

        <?php echo get_tabs_social('Routines'); ?>
        <br><br>
        <?php
            $search_staff = '';
            $search_client = '';
            $search_status = '';

            if(isset($_GET['search_staff']) && $_GET['search_staff']!='') {
                $search_staff = $_GET['search_staff'];    
            } 
            if(isset($_GET['search_client']) && $_GET['search_client']!='') {
                $search_client = $_GET['search_client'];    
            }
            if(isset($_GET['search_status']) && $_GET['search_status']!='') {
                $search_status = $_GET['search_status'];    
            }
        ?>  

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                  <label for="site_name" class="control-label">Search By Staff:</label>
                </div>
                  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                      <select data-placeholder="Pick a Type" name="search_staff" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
                      <option value=""></option>
                      <?php
                        $query = mysqli_query($dbc,"SELECT distinct(support_contact) FROM social_story_routines WHERE support_contact_category = 'Staff' order by support_contact");
                        while($row1 = mysqli_fetch_array($query)) {
                        ?><option <?php if ($row1['support_contact'] == $search_staff) { echo " selected"; } ?> value='<?php echo  $row1['support_contact']; ?>' ><?php echo get_staff($dbc, $row1['support_contact']); ?></option>
                    <?php   }
                    ?>
                    </select>
                  </div>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                  <label for="site_name" class="control-label">Search By Client:</label>
                </div>
                  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                      <select data-placeholder="Pick a Type" name="search_client" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
                      <option value=""></option>
                      <?php
                        $query = mysqli_query($dbc,"SELECT distinct(support_contact) FROM social_story_routines WHERE support_contact_category = 'Clients' order by support_contact");
                        while($row1 = mysqli_fetch_array($query)) {
                        ?><option <?php if ($row1['support_contact'] == $search_client) { echo " selected"; } ?> value='<?php echo  $row1['support_contact']; ?>' ><?php echo get_staff($dbc, $row1['support_contact']); ?></option>
                    <?php   }
                    ?>
                    </select>
                  </div>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                  <label for="site_name" class="control-label">Search By Status:</label>
                </div>
                  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                      <select data-placeholder="Choose a Status..." name="search_status" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
                          <option value=""></option>
                          <option value="Active" <?php if($search_status == 'Active') echo 'selected="selected"'; ?>>Active</option>
                          <option value="Inactive" <?php if($search_status == 'Inactive') echo 'selected="selected"'; ?>>Inactive</option>
                          <option value="Review" <?php if($search_status == 'Review') echo 'selected="selected"'; ?>>Review</option>
                        </select>
                  </div>

                <div class="form-group">
                  <label for="site_name" class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
                    <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
                    <button type="button" onclick="window.location='routines.php'" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
                  </div>
                </div>

			<?php $from_url = 'routines.php';
			include('../Social Story/routines_list.php'); ?>
			
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
