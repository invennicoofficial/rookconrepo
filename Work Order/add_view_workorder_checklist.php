<script type="text/javascript">
$(document).ready(function() {
    $("#add_checklist").on("click", function () {
        var task_other_name = $("#task_other").val();
        $("ul.add-task").append('<ul><li>'+task_other_name+'</li></ul>');
        $("#task_other").val('');
		var workorderid = $('#workorderid').val();
        var checklist = escape(task_other_name);

        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "workorder_ajax_all.php?fill=gochecklist&checklist="+checklist+"&workorderid="+workorderid,
            dataType: "html",   //expect html to be returned
            success: function(response) {
				$("#checklist").val('');
                $("#table2").append('<tr><td><input type="checkbox" class="rowLinkOther" value="'+task_other_name+'" name="travel_task[]"></td><td><label style="padding-left:20px" for="data_patient-satisfaction">'+task_other_name+'</label></td></tr>');
                //location.reload();
            }
        });
        return false;

    });
});

function checkedItem(sel) {
    //if(this.checked) {
        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "workorder_ajax_all.php?fill=checklistdone&workorderchecklistid="+sel.id,
            dataType: "html",   //expect html to be returned
            success: function(response){
                location.reload();
            }
        });
    //}
}

</script>
<form id="form1" name="form1" method="post"	action="add_view_workorder_tasklist.php" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="col-md-12">

      <div class="form-group">
        <label for="task_other" class="col-sm-4 control-label">Checklist of Items:</label>
        <div class="col-sm-4">
          <input type="text" name="task_other" id="task_other" class="form-control" />
        </div>
        <div class="col-sm-4">
          <button name="add_checklist" id="add_checklist" value="Generate" class="btn brand-btn pull-right collapse_checklist">Add to Checklist</button>
        </div>
      </div>

        <?php
        $query_check_credentials = "SELECT * FROM workorder_checklist WHERE workorderid='$workorderid'";
        $result = mysqli_query($dbc, $query_check_credentials);

            echo '<div class="col-sm-offset-4 col-sm-8 double-pad-top triple-pad-bottom">
            <div class="col-sm-6">
              <table id="table2" class="displayTable pad-top">
                <tbody>';

            while($row = mysqli_fetch_array($result)) {
                $workorderchecklistid = $row['workorderchecklistid'];
                $key = $row['checklist'];
                $done = $row['checked'];
                if($key != '') {
                    $disable = '';
                    $checked = '';
                    $style = '';
                    if($done == 1) {
                        $disable = ' disabled';
                        $checked = ' checked';
                        $style='text-decoration:line-through; padding-left:20px;';
                    } else {
                        $style='padding-left:20px;';
                    }
                    echo "<tr><td><input onchange='checkedItem(this)' type='checkbox' ".$disable." ".$checked." id='".$workorderchecklistid."' class='rowLinkOther' value='".$key."'></td><td><label for='data_patient-satisfaction' class='cl-text' style='".$style."' >".$key."</label> </td></tr>
                    ";
                }
            }
            echo '</tbody>
              </table>
            </div>
            </div>
            ';
             ?>


      <div class="col-sm-offset-4 col-sm-8 double-pad-top triple-pad-bottom">
        <div class="col-sm-6">
          <table id="table2" class="displayTable pad-top">
            <tbody></tbody>
          </table>
        </div>
        </div>

</div>
</form>