<?php

namespace App\CustomModel;

use Illuminate\Support\Facades\DB;

class ConfigModel
{
    public function getFirstSuiteTime(){
        $sql = "SELECT Ref_Desc FROM mas_reference WHERE Ref_Code = 'SU_F_RND' AND Ref_Type ='SUITE_FEE'";

        $time =  collect(\DB::select($sql))->first();

        return $time->Ref_Desc;
    }
    public function getNextSuiteTime(){
        $sql = "SELECT Ref_Desc FROM mas_reference WHERE Ref_Code = 'SU_RND' AND Ref_Type ='SUITE_FEE'";

        $time = collect(\DB::select($sql))->first();

        return $time->Ref_Desc;
    }
    public function getOpenTime(){
        $sql = "SELECT Ref_Desc FROM mas_reference WHERE Ref_Code = 'OPEN' AND Ref_Type ='TIME'";

        $time = collect(\DB::select($sql))->first();

        return $time->Ref_Desc;
    }
    public function getCreditCartType(){
        $sql = "SELECT Ref_Code,Ref_Desc FROM mas_reference WHERE  Ref_Type ='CREDITCARD_TYPE'";
        $list = DB::select($sql);

        return $list;
    }
    public function getMakeupPrice(){
        $sql = "SELECT Ref_Code,Ref_Desc FROM mas_reference WHERE  Ref_Type ='ANGEL_PAYMENT' AND Ref_Code ='MAKEUP'";
        $list = collect(\DB::select($sql))->first();

        return $list;
    }
    public function editConfig($code,$type,$value){
        $sql = "UPDATE mas_reference SET Ref_Desc= ? WHERE Ref_Type = ? AND Ref_Code = ?";
        $res = DB::update($sql,[$value,$type,$code]);
        return $res;
    }
    public function getConfig($code,$type){
        $sql = "SELECT Ref_Code,Ref_Desc FROM mas_reference WHERE  Ref_Type = ? AND Ref_Code =?";
        $list = collect(\DB::select($sql,[$type,$code]))->first();

        return $list->Ref_Desc;
    }
}
