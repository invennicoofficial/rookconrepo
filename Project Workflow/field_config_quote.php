<script type="text/javascript">
$(document).ready(function() {
    $('#add_tax_button').on( 'click', function () {
        var clone = $('.additional_tax').clone();

        var numItems = $('.tax_exemption_div').length;
        clone.find('.tax_exemption').attr("name", "quote_tax_exemption_"+numItems);
        clone.find('.form-control').val('');
        clone.find('.rate').val('0');
        clone.removeClass("additional_tax");
        $('#add_here_new_tax').append(clone);
        return false;
    });
});
</script>

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Logo for PDF<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group">
                <label for="file[]" class="col-sm-4 control-label">Header Logo
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
                </span>
                :</label>
                <div class="col-sm-8">
                <?php if($pdf_logo != '') {
                    echo '<a href="download/'.$pdf_logo.'" target="_blank">View</a>';
                    ?>
                    <input type="hidden" name="logo_file" value="<?php echo $pdf_logo; ?>" />
                    <input name="pdf_logo" type="file" data-filename-placement="inside" accept="image/*" class="form-control" />
                  <?php } else { ?>
                  <input name="pdf_logo" type="file" data-filename-placement="inside" accept="image/*" class="form-control" />
                  <?php } ?>
                </div>
                </div>

                <div class="form-group">
                <label for="file[]" class="col-sm-4 control-label">Footer Logo
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
                </span>
                :</label>
                <div class="col-sm-8">
                <?php if($pdf_footer_logo != '') {
                    echo '<a href="download/'.$pdf_footer_logo.'" target="_blank">View</a>';
                    ?>
                    <input type="hidden" name="pdf_footer_logo_file" value="<?php echo $pdf_footer_logo; ?>" />
                    <input name="pdf_footer_logo" type="file" data-filename-placement="inside" accept="image/*" class="form-control" />
                  <?php } else { ?>
                  <input name="pdf_footer_logo" type="file" data-filename-placement="inside" accept="image/*" class="form-control" />
                  <?php } ?>
                </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4">
                        <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>" class="btn brand-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="inv_pdf"	value="inv_dashboard" class="btn brand-btn pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Header" >
                    Header & Footer for PDF<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_Header" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Header Info:<br><em>(e.g. - company address, phone, email, etc.)</em></label>
                    <div class="col-sm-8">
                        <textarea name="pdf_header" rows="3" cols="50" class="form-control"><?php echo $pdf_header; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Footer Info:<br><em>(e.g. - company name, address, phone, etc.)</em></label>
                    <div class="col-sm-8">
                        <textarea name="pdf_footer" rows="3" cols="50" class="form-control"><?php echo $pdf_footer; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>" class="btn brand-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="inv_pdf"	value="inv_dashboard" class="btn brand-btn pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_survey" >
                    Email PDF To Client<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_survey" class="panel-collapse collapse">
            <div class="panel-body">

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                <div class="col-sm-8">
                    <input name="send_pdf_client_subject" type="text" value = "<?php echo $send_pdf_client_subject; ?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use tag for Client Name: [Client Name]</label>
                <div class="col-sm-8">
                    <textarea name="send_pdf_client_body" rows="5" cols="50" class="form-control"><?php echo $send_pdf_client_body; ?></textarea>
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>" class="btn brand-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="inv_pdf"	value="inv_dashboard" class="btn brand-btn pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_term" >
                    Payment Breakdown<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_term" class="panel-collapse collapse">
            <div class="panel-body">

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Payment Breakdown:<br>(e.g. - retainer, deposit, midpoint, completion &nbsp;% amounts)</label>
                <div class="col-sm-8">
                    <input name="pdf_payment_term" type="text" value = "<?php echo $pdf_payment_term; ?>" class="form-control">
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>" class="btn brand-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="inv_pdf"	value="inv_dashboard" class="btn brand-btn pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_termcond" >
                    Terms & Condition<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_termcond" class="panel-collapse collapse">
            <div class="panel-body">

               <?php
                $quote_term_condition = get_config($dbc, 'quote_term_condition');
               ?>

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Payment Term:</label>
                <div class="col-sm-8">
                    <input name="quote_term_condition" type="text" value = "<?php //echo $quote_term_condition; ?>" class="form-control">
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="estimate.php" class="btn config-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_due" >
                   Payment Due Period<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_due" class="panel-collapse collapse">
            <div class="panel-body">

              <div class="form-group">
                <label for="fax_number"	class="col-sm-4	control-label">Payment Due Within:</label>
                <div class="col-sm-8">
                    <input name="pdf_due_period" type="text" value = "<?php echo $pdf_due_period; ?>" class="form-control">
                </div>
              </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>" class="btn brand-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="inv_pdf"	value="inv_dashboard" class="btn brand-btn pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tax" >
                    Set Tax Names & Rates<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_tax" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group clearfix  hide-titles-mob">
                    <label class="col-sm-2 text-center">Name</label>
                    <label class="col-sm-2 text-center">Rate(%)<br><em>(add number without % sign)</em></label>
                    <label class="col-sm-2 text-center">Tax Number</label>
                </div>

                <?php
                $quote_tax = explode('*#*',$pdf_tax);

                $total_count = mb_substr_count($pdf_tax,'*#*');
                for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
                    $quote_tax_name_rate = explode('**',$quote_tax[$eq_loop]);
                ?>
                    <div class="clearfix"></div>
                    <div class="form-group clearfix">
                      <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Name:</label>
                            <input name="quote_tax_name[]" value="<?php echo $quote_tax_name_rate[0];?>" type="text" class="form-control quantity" />
                        </div>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate (%) (add number without % sign):</label>
                            <input name="quote_tax_rate[]" value="<?php echo $quote_tax_name_rate[1]; ?>" type="text" class="form-control category" />
                        </div>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Tax Number:</label>
                            <input name="quote_tax_number[]" value="<?php echo $quote_tax_name_rate[2]; ?>" type="text" class="form-control category" />
                        </div>
                    </div>
                <?php } ?>

                <div class="additional_tax">
                <div class="clearfix"></div>
                <div class="form-group clearfix" width="100%">
                    <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Name:</label>
                        <input name="quote_tax_name[]" type="text" class="form-control price" />
                    </div>
                    <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate (%) (add number without % sign):</label>
                        <input name="quote_tax_rate[]" value="0" type="text" class="form-control rate" />
                    </div>
                    <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Tax Number:</label>
                        <input name="quote_tax_number[]" type="text" class="form-control" />
                    </div>
                </div>

                </div>

                <div id="add_here_new_tax"></div>

                <div class="col-sm-8 col-sm-offset-4 triple-gap-bottom">
                    <button id="add_tax_button" class="btn brand-btn mobile-block">Add</button>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 clearfix">
                        <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>" class="btn brand-btn pull-right">Back</a>
                    </div>
                    <div class="col-sm-8">
                        <button	type="submit" name="inv_pdf"	value="inv_dashboard" class="btn brand-btn pull-right">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="form-group double-gap-top">
        <div class="col-sm-6">
           <a href="project_workflow_dashboard.php?tile=<?php echo $_GET['tile'];?>&tab=<?php echo $_GET['tab']; ?>" class="btn config-btn btn-lg">Back</a>
        </div>
        <div class="col-sm-6">
            <button	type="submit" name="inv_pdf" value="inv_dashboard" class="btn config-btn btn-lg	pull-right">Submit</button>
        </div>
		<div class="clearfix"></div>
    </div>

</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>