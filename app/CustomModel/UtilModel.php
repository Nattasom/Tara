<?php

namespace App\CustomModel;

use Illuminate\Support\Facades\DB;

class UtilModel
{
    public function minsToHourString($mins){
        if($mins < 0){
            $mins = $mins * -1;
        }
        $str = "";
        $hours = (int)($mins/60);
        $mins = $mins % 60;
        $str .= str_pad($hours,2,'0',STR_PAD_LEFT);
        $str .=":";
        $str .= str_pad($mins,2,'0',STR_PAD_LEFT);
        return $str;
    }
    public function dateToThaiFormat($date){ //from yyyy-mm-dd
        $str = "";
        $arrDate = explode("-",$date);
        if(count($arrDate)==3){
            $str = $arrDate[2]."/".$arrDate[1]."/".(intval($arrDate[0])+543);
        }

        return $str;
    }
    public function dateThaiToSystemFormat($date){ //from dd/mm/yyyy
        $str = "";
        $arrDate = explode("/",$date);
        if(count($arrDate)==3){
            $str = (intval($arrDate[2])-543)."-".$arrDate[1]."-".$arrDate[0];
        }

        return $str;
    }
    public function timeStringToNumber($str){
        $arrTime = explode(':',$str);
        $minutes = intval($arrTime[0])*60;
        $minutes += intval($arrTime[1]);
        return $minutes;
    }
    public function numberFormat($value,$digit = 0){
        if(empty($value)){
            $value = 0;
        }
        if($digit==0){
            return number_format($value);
        }
        else{
            return number_format($value,$digit);
        }
    }
    public function numberNoDigit($num){
        $arr = explode(".",$num);

        return $arr[0];
    }
}
