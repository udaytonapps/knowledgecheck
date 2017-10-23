<?php

class KC_Utils {

    public static function compareQNum($a, $b) {

        if ($a["QNum"] == $b["QNum"]) {
            return 0;
        }
        return ($a["QNum"] < $b["QNum"]) ? -1 : 1;
    }

    public static function compareQNum2($a, $b) {

        if ($a["QNum2"] == $b["QNum2"]) {
            return 0;
        }
        return ($a["QNum2"] < $b["QNum2"]) ? -1 : 1;
    }

    // Comparator for student last name used for sorting roster
    public static function compareStudentsLastName($a, $b) {
        return strcmp($a["person_name_family"], $b["person_name_family"]);
    }

}