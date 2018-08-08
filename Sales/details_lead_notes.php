<!-- Lead Notes -->
<div class="accordion-block-details padded" id="leadnotes">
    <div class="accordion-block-details-heading"><h4>Lead Notes</h4></div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-11 gap-md-left-15"><?php
            if ( !empty($salesid) ) {
                $result = mysqli_query($dbc, "SELECT * FROM `sales_notes` WHERE `salesid`='{$salesid}' ORDER BY `salesnoteid` DESC");
                if($result->num_rows > 0) {
                    $odd_even = 0;
                    echo '
                        <br />
                        <table class="table">
                            <tr class="hidden-xs hidden-sm">
                                <th>Note</th>
                                <th>Date</th>
                                <th>Assign To</th>
                                <th>Added By</th>
                            </tr>';
                    
                    while($row = mysqli_fetch_array($result)) {
                        $bg_class = $odd_even % 2 == 0 ? 'row-even-bg' : 'row-odd-bg';
                        echo '<tr class="'.$bg_class.'">';
                            $by = $row['created_by'];
                            $to = $row['email_comment'];
                            //echo '<td data-title="Schedule">'. $row['note_heading'] .'</td>';
                            echo '<td data-title="Note">'. html_entity_decode($row['comment']) .'</td>';
                            echo '<td data-title="Date">'. $row['created_date'] .'</td>';
                            echo '<td data-title="Assign To">'. get_staff($dbc, $to) .'</td>';
                            echo '<td data-title="Added By">'. get_staff($dbc, $by) .'</td>';
                            //echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&ticketcommid='.$row['ticketcommid'].'&ticketid='.$row['ticketid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                        echo '</tr>';
                        $odd_even++;
                    }
                    
                    echo '</table><br /><br />';
                }
            } ?>
        </div>
    </div>
    
    <!--
    <div class="row set-row-height triple-gap-top">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Note Heading:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Choose a Heading..." name="note_heading" class="chosen-select-deselect form-control" width="380">
                <option value=""></option>
                <option value="Actual Outcome">Actual Outcome</option>
                <option value="Adjustments Needed">Adjustments Needed</option>
                <option value="Already Known">Already Known</option>
                <option value="Audience">Audience</option>
                <option value="Base Knowledge">Base Knowledge</option>
                <option value="Check">Check</option>
                <option value="Current Designs">Current Designs</option>
                <option value="Desired Outcome">Desired Outcome</option>
                <option value="Discovered">Discovered</option>
                <option value="Do">Do</option>
                <option value="Future Designs">Future Designs</option>
                <option value="GAP">GAP</option>
                <option value="General">General</option>
                <option value="Issue">Issue</option>
                <option value="Known Techniques">Known Techniques</option>
                <option value="Learnt">Learned</option>
                <option value="Looking to Achieve">Looking to Achieve</option>
                <option value="Next Steps">Next Steps</option>
                <option value="Objective">Objective</option>
                <option value="Plan">Plan</option>
                <option value="Problem">Problem</option>
                <option value="Review Needed">Review Needed</option>
                <option value="Sources">Sources</option>
                <option value="Strategy">Strategy</option>
                <option value="Targets">Targets</option>
                <option value="Technical Uncertainty">Technical Uncertainty</option>
                <option value="Tech Advancements">Tech Advancements</option>
                <option value="Work">Work</option>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
    -->
    
    <div class="row">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Note:</div>
        <div class="col-xs-12 col-sm-7"><textarea name="comment" rows="4" cols="50" class="form-control"></textarea></div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Send Email:</div>
        <div class="col-xs-12 col-sm-5"><input type="checkbox" value="Yes" name="send_email_on_comment" /></div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Assign/Email To:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Choose a Staff Member..." name="email_comment" class="chosen-select-deselect form-control" width="380">
                <option value=""></option><?php
                $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted`=0 AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status`>0"), MYSQLI_ASSOC));
                foreach($query as $id) { ?>
                    <option value="<?= $id; ?>"><?= get_contact($dbc, $id); ?></option><?php
                } ?>
			</select>
        </div>
        <div class="clearfix"></div>
    </div>
    
</div><!-- .accordion-block-details -->