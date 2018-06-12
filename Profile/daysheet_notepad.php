<!-- Daysheet Notepad -->
<?php
$daysheet_notes = '';
$notepad_query = "SELECT * FROM `daysheet_notepad` WHERE `contactid` = '".$contactid."' AND `date` = '".$daily_date."'";
$notepad_result = mysqli_fetch_assoc(mysqli_query($dbc, $notepad_query));
?>
<script type="text/javascript">
$(document).ready(function() {
    $('[name="daysheet_notepad"]').change(function() {
        var notes = this.value;
        var date = $('[name="daily_date"]').val();
        var contactid = $('[name="daysheet_contactid"]').val();
        $.ajax({
            url: '../Profile/profile_ajax.php?fill=daysheet_notepad',
            method: 'POST',
            data: {
                notes: notes,
                date: date,
                contactid: contactid
            },
            success: function(response) {
            }
        });
    });
    tinymce.on('AddEditor', function (e) {
        tinymce.editors[e.editor.id].on('blur',function() {
            this.save();
            $(this.getElement()).change();
        });
    });
});
</script>

<textarea name="daysheet_notepad"><?= html_entity_decode($notepad_result['notes']) ?></textarea>