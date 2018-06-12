<div class="form-group clearfix">
    <div class="col-sm-8">
    <?php

    $query = mysqli_query($dbc,"SELECT ticketid, heading FROM tickets WHERE businessid='$businessid' AND status='Customer QA'");

    while($row = mysqli_fetch_array($query)) {
        ?>
        <input type="checkbox" <?php if (strpos(','.$qa_ticket.',', ','.$row['ticketid'].',') !== FALSE) { echo " checked"; } ?> value="<?php echo $row['ticketid']; ?>" style="height: 20px; width: 20px;" name="qa_ticket[]">&nbsp;&nbsp;<?php echo $row['heading']; ?>
        <br>
        <?php
    }
    ?>
    </div>
</div>