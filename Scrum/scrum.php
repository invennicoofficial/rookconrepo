<?php
/*
Inventory Listing
*/
include ('../include.php');
?>
<script>
    function submitForm(thisForm) {
        if (!$('input[name="search_user_submit"]').length) {
            var input = $("<input>")
                        .attr("type", "hidden")
                        .attr("name", "search_user_submit").val("1");
            $('[name=form_sites]').append($(input));
        }

        $('[name=form_sites]').submit();
    }
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('scrum');

include('scrum_display.php'); ?>