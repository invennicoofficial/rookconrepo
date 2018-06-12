          <?php if($action == 'view') { ?>
           <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Link(s):</label>
            <div class="col-sm-8">
                <?php
                    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(uploadid) AS total_id FROM manuals_upload WHERE type='link' AND manualtypeid='$manualtypeid'"));

                    if((!empty($_GET['manualtypeid'])) && ($get_doc['total_id'] > 0)) {
                        $result = mysqli_query($dbc, "SELECT upload, uploadid FROM manuals_upload WHERE type='link' AND manualtypeid='$manualtypeid'");

                        echo '<ul>';
                        $i=0;
                        while($row = mysqli_fetch_array($result)) {
                            $link = $row['upload'];
                            if($link != '') {
                                echo '<li><a href="'.$link.'" target="_blank">'.$link.'</a></li>';
                            }
                        }
                        echo '</ul>';
                    }
                ?>
            </div>
          </div>
          <?php } else { ?>

            <div class="form-group">
                <label for="additional_note" class="col-sm-4 control-label">Add Link(s):<br><em>(e.g. - https://www.google.com)</em>
                </label>
                <div class="col-sm-8">

                <?php
                    if(!empty($_GET['manualtypeid'])) {
                        $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(uploadid) AS total_id FROM manuals_upload WHERE type='link' AND manualtypeid='$manualtypeid'"));

                        if($get_doc['total_id'] > 0) {
                            $result = mysqli_query($dbc, "SELECT upload, uploadid FROM manuals_upload WHERE type='link' AND manualtypeid='$manualtypeid'");

                            echo '<ul>';
                            $i=0;
                            while($row = mysqli_fetch_array($result)) {
                                $link = $row['upload'];
                                if($link != '') {
                                    echo '<li><a href="'.$link.'" target="_blank">'.$link.'</a> - <a href="add_manual.php?action=delete&uploadid='.$row['uploadid'].'&manualtypeid='.$manualtypeid.'&type='.$type.'" onclick="return confirm(\'Are you sure?\')">Delete</a></li>';
                                }
                            }
                            echo '</ul>';
                        }
                    }
                ?>

                    <div class="enter_cost additional_link clearfix">
                        <div class="clearfix"></div>

                        <div class="form-group clearfix">
                            <div class="col-sm-5">
                                <input name="link[]" type="text" class="form-control"/>
                            </div>
                        </div>

                    </div>

                    <div id="add_here_new_link"></div>

                    <div class="form-group triple-gapped clearfix">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button id="add_row_link" class="btn brand-btn pull-left">Add Another Link</button>
                        </div>
                    </div>
                </div>
            </div>
          <?php } ?>