<?php

    function display_filter($file_name) {
        ?><style>.filter_div a { color:#2dd1d9; } .filter_div a:hover { color:#86244E; } </style><div class='filter_div' style='text-align:center;font-size:18px; margin-bottom:20px;'><?php

        $style = '';
        if(isset($_GET['filter'])) {
            if($_GET['filter'] == 'Top') {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
        } else {
             $style = "style='color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;'";
        }
        echo "<a  href='".$file_name."?filter=Top' style='width:30px; display:inline-block;".$style."'>Top </a>\n";

        $style = '';
        if(isset($_GET['filter'])) {
            if($_GET['filter'] == 'All') {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
        } else {
             $style = "style='color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;'";
        }
        echo "<a  href='".$file_name."?filter=All' style='width:30px; display:inline-block;".$style."'>All</a>\n";

        foreach (range('A', 'Z') as $char) {
            $style = '';
            if(isset($_GET['filter'])) {
            if($_GET['filter'] == $char) {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
            }
            echo '<a  style="width:30px; display:inline-block;'.$style.'" href="'.$file_name.'?filter='.$char.'">'.$char . "</a>\n";
        }

        $style = '';
        if(isset($_GET['filter'])) {
            if($_GET['filter'] == '0') {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
        }
        echo "<a  style='width:30px; display:inline-block;".$style."' href='".$file_name."?filter=0'>0</a>\n";

        foreach (range('1', '9') as $number) {
            $style = '';
            if(isset($_GET['filter'])){
            if($_GET['filter'] == $number) {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
            }
            echo '<a style="width:30px; display:inline-block;'.$style.'" href="'.$file_name.'?filter='.$number.'">'.$number . "</a>\n";
        }
        echo '</div>';
	}

    function display_filter_param($file_name) {
        ?><style>.filter_div a { color:#2dd1d9; } .filter_div a:hover { color:#86244E; } </style><div class='filter_div' style='text-align:center;font-size:18px; margin-bottom:20px;'><?php

        $style = '';
        if(isset($_GET['filter'])) {
            if($_GET['filter'] == 'Top') {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
        } else {
             $style = "style='color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;'";
        }
        echo "<a  href='".$file_name."&filter=Top' style='width:30px; display:inline-block;".$style."'>Top </a>\n";

        $style = '';
        if(isset($_GET['filter'])) {
            if($_GET['filter'] == 'All') {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
        } else {
             $style = "style='color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;'";
        }
        echo "<a  href='".$file_name."&filter=All' style='width:30px; display:inline-block;".$style."'>All</a>\n";

        foreach (range('A', 'Z') as $char) {
            $style = '';
            if(isset($_GET['filter'])) {
            if($_GET['filter'] == $char) {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
            }
            echo '<a  style="width:30px; display:inline-block;'.$style.'" href="'.$file_name.'&filter='.$char.'">'.$char . "</a>\n";
        }

        $style = '';
        if(isset($_GET['filter'])) {
            if($_GET['filter'] == '0') {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
        }
        echo "<a  style='width:30px; display:inline-block;".$style."' href='".$file_name."&filter=0'>0</a>\n";

        foreach (range('1', '9') as $number) {
            $style = '';
            if(isset($_GET['filter'])){
            if($_GET['filter'] == $number) {
                $style = "color: #86244E; cursor: default; font-weight:bold; text-decoration:underline;";
            }
            }
            echo '<a style="width:30px; display:inline-block;'.$style.'" href="'.$file_name.'&filter='.$number.'">'.$number . "</a>\n";
        }
        echo '</div>';
	}

?>
