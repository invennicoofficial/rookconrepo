<?php
/*
Add	Site
*/
include ('../include.php');
checkAuthorised('routine');

if (isset($_POST['submit_routine'])) {

    $task = $_POST['task'];
    $upload_image = htmlspecialchars($_FILES["upload_image"]["name"], ENT_QUOTES);
    $upload_video = htmlspecialchars($_FILES["upload_video"]["name"], ENT_QUOTES);
    $category = $_POST['category'];

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    $N = count($task);

    for($i=0; $i < $N; $i++) {

        if(($task[$i] != NULL) && ($task[$i] != '')) {

            move_uploaded_file($_FILES["upload_image"]["tmp_name"][$i], "download/" . $_FILES["upload_image"]["name"][$i]) ;

            move_uploaded_file($_FILES["upload_video"]["tmp_name"][$i], "download/" . $_FILES["upload_video"]["name"][$i]) ;

            $query_insert_routine = "INSERT INTO `routine` (`task`, `upload_image`, `upload_video`, `category`) VALUES ('$task[$i]', '$upload_image[$i]', '$upload_video[$i]', '$category[$i]')";
            $result_insert_routine = mysqli_query($dbc, $query_insert_routine);
        }
    }

    echo '<script type="text/javascript"> window.location.replace("routine.php"); </script>';

}

?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.hide_detail').hide();
        $('#add_row').on( 'click', function () {
            var clone = $('.additional_detail').clone();
            clone.find('.form-control').val('');
            clone.removeClass("additional_detail");
            $('#add_here_new_detail').append(clone);
            return false;
        });
    });
</script>
</head>

<body>
<?php include_once ('../navigation.php'); ?>

<div class="container">
    <div class="row">

		<h1	class="triple-pad-bottom">Add A	New	Routine</h1>

        <form name="routine" method="post" action="add_routine.php" enctype="multipart/form-data"  class="form-horizontal" role="form">

        <div class="form-group clearfix">
            <label class="col-sm-3 text-center">Task</label>
            <label class="col-sm-3 text-center">Upload Image</label>
            <label class="col-sm-3 text-center">Upload Video</label>
            <label class="col-sm-3 text-center">Category</label>
        </div>
        <div class="clearfix"></div>

        <div class="form-group clearfix">
            <div class="col-sm-3">
                <input type="text" name="task[]" class="form-control">
            </div>
            <div class="col-sm-3">
                <input name="upload_image[]" type="file" class="form-control" />
            </div>
            <div class="col-sm-3">
                <input name="upload_video[]" type="file" class="form-control" />
            </div>
           <div class="col-sm-3">
               <select data-placeholder="Choose a Category..." name="category[]" class="chosen-select-deselect form-control" width="380">
                <option value=""></option>
                <option value="Morning">Morning</option>
                <option value="Afternoon">Afternoon</option>
                <option value="Evening">Evening</option>
                <option value="Night">Night</option>
              </select>
            </div>
        </div>

        <div class="hide_detail">
            <div class="form-group clearfix">
                <label class="col-sm-3 text-center">Task</label>
                <label class="col-sm-3 text-center">Upload Image</label>
                <label class="col-sm-3 text-center">Upload Video</label>
                <label class="col-sm-3 text-center">Category</label>
            </div>
            <div class="clearfix"></div>

            <div class="form-group additional_detail clearfix">
                <div class="col-sm-3">
                    <input type="text" name="task[]" class="form-control" >
                </div>
                <div class="col-sm-3">
                    <input name="upload_image[]" type="file" class="form-control" />
                </div>
                <div class="col-sm-3">
                    <input name="upload_video[]" type="file" class="form-control" />
                </div>
                <div class="col-sm-3">
                   <select data-placeholder="Choose a Category..." name="category[]" class="chosen-select-deselect1 form-control" width="380">
                    <option value=""></option>
                    <option value="Morning">Morning</option>
                    <option value="Afternoon">Afternoon</option>
                    <option value="Evening">Evening</option>
                    <option value="Night">Night</option>
                  </select>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div id="add_here_new_detail"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row" class="btn brand-btn mobile-block pull-right">Add Row</button>
            </div>
        </div>

          <div class="form-group">
            <div class="col-sm-4">
                <a href="routine.php" class="btn brand-btn mobile-block">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit_routine" value="Add" class="btn brand-btn mobile-block pull-right">Submit</button>
            </div>
          </div>

        </form>

	</div>
</div>

<?php include ('../footer.php'); ?>