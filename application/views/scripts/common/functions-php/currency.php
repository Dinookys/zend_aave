<?php

function currency($val)
{
    $val = preg_replace('/([^\d]*)/i', "", $val);
    
    if (strlen($val) < 3) {
        return $val . ',00';
    }
    
    if (strlen($val) == 3) {
        return preg_replace('/([0-9]{1})([0-9])/i', "$1,$2", $val);
    }
    
    if (strlen($val) == 4) {
        return preg_replace('/([0-9]{2})([0-9])/i', "$1,$2", $val);
    }
    
    if (strlen($val) == 5) {
        return preg_replace('/([0-9]{3})([0-9])/i', "$1,$2", $val);
    }
    
    if (strlen($val) == 6) {
        return preg_replace('/([0-9]{4})([0-9])/i', "$1,$2", $val);
    }
    
    if (strlen($val) == 7) {
        return preg_replace('/([0-9]{2})([0-9]{3})([0-9])/i', "$1.$2,$3", $val);
    }
    
    if (strlen($val) == 8) {
        return preg_replace('/([0-9]{3})([0-9]{3})([0-9])/i', "$1.$2,$3", $val);
    }
    
    if (strlen($val) == 9) {
        return preg_replace('/([0-9]{1})([0-9]{3})([0-9]{3})([0-9])/i', "$1.$2.$3,$4", $val);
    }
    
    if (strlen($val) > 9) {
        return preg_replace('/([0-9]{1})([0-9]{3})([0-9]{3})([0-9])/i', "$1.$2.$3,$4", $val);
    }
}