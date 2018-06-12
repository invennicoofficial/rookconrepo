<?php

    function display_filter($file_name) {
        ?><style>.filter_div a { color:#2dd1d9; } .filter_div a:hover { color:green; } </style><div class='filter_div' style='text-align:center;font-size:18px; margin-bottom:20px;'><?php
        $style = '';
        if(isset($_GET['filter'])) {
            if($_GET['filter'] == 'All') {
                $style = "color: green; cursor: default; font-weight:bold; text-decoration:underline;";
            }
        } else {
             $style = "style='color: green; cursor: default; font-weight:bold; text-decoration:underline;'";
        }
        echo "<a  href='".$file_name."?filter=All' style='width:30px; display:inline-block;".$style."'>All</a>\n";

        foreach (range('A', 'Z') as $char) {
            $style = '';
            if(isset($_GET['filter'])) {
            if($_GET['filter'] == $char) {
                $style = "color: green; cursor: default; font-weight:bold; text-decoration:underline;";
            }
            }
            echo '<a  style="width:30px; display:inline-block;'.$style.'" href="'.$file_name.'?filter='.$char.'">'.$char . "</a>\n";
        }

        $style = '';
         if(isset($_GET['filter'])) {
        if($_GET['filter'] == '0') {
            $style = "color: green; cursor: default; font-weight:bold; text-decoration:underline;";
        }
         }
        echo "<a  style='width:30px; display:inline-block;".$style."' href='".$file_name."?filter=0'>0</a>\n";

        foreach (range('1', '9') as $number) {
            $style = '';
            if(isset($_GET['filter'])){
            if($_GET['filter'] == $number) {
                $style = "color: green; cursor: default; font-weight:bold; text-decoration:underline;";
            }
            }
            echo '<a style="width:30px; display:inline-block;'.$style.'" href="'.$file_name.'?filter='.$number.'">'.$number . "</a>\n";
        }
        echo '</div>';
	}

    function patient_filter($file_name) {
        ?><style>.filter_div a { color:#2dd1d9; } .filter_div a:hover { color:green; } </style><div class='filter_div' style='text-align:center;font-size:18px; margin-bottom:20px;'><?php
        $style = '';
        if(isset($_GET['filter'])) {
        } else {
             $style = "style='color: green; cursor: default; font-weight:bold; text-decoration:underline;'";
        }
        echo 'Patient First Name Starting with : ';

        foreach (range('A', 'Z') as $char) {
            $style = '';
            if(isset($_GET['filter'])) {
            if($_GET['filter'] == $char) {
                $style = "color: green; cursor: default; font-weight:bold; text-decoration:underline;";
            }
            }
            echo '<a  style="width:30px; display:inline-block;'.$style.'" href="'.$file_name.'?filter='.$char.'">'.$char . "</a>";
        }

        echo '</div>';
	}

?>
