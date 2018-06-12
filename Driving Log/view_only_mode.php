<script type="text/javascript">
$(document).ready(function() {
    $('.view_only_button').click(function() {
        var contactid = '<?= $_SESSION['contactid'] ?>';
        var viewonlymode = '<?= $view_only_mode ?>';
        $.ajax({
            type: "GET",
            url: "driving_log_ajax_all.php?fill=toggleviewmode&contactid="+contactid+"&viewonlymode="+viewonlymode,
            dataType: "html",
            cache: false,
            success: function(response) {
                location.reload();
            }
        });
    });
});
</script>