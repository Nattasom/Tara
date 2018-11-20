<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\ConfigModel;
use App\CustomModel\UtilModel;
class Setting extends Controller
{
    private $config;
    private $util;
    function __construct(){
        $this->config = new ConfigModel();
        $this->util = new UtilModel();
    }
    function index(Request $request){
        $status = "";
        $message = "";
        if($request->isMethod('post')){
            $this->config->editConfig("SU_F_RND","SUITE_FEE",$request->input("SU_F_RND"));
            $this->config->editConfig("SU_RND","SUITE_FEE",$request->input("SU_RND"));
            $this->config->editConfig("OPEN","TIME",$request->input("OPEN"));
            $this->config->editConfig("MAKEUP","ANGEL_PAYMENT",$request->input("MAKEUP"));
            $this->config->editConfig("OT_FEE","ANGEL_FEE",$request->input("OT_FEE"));
            $this->config->editConfig("OT_WAGE","ANGEL_FEE",$request->input("OT_WAGE"));
            $status="01";
            $message = "บันทึกข้อมูลเรียบร้อย";
        }
        $data["first_time_suite"] = $this->config->getFirstSuiteTime();
        $data["time_suite"] = $this->config->getNextSuiteTime();
        $data["time_open"] = $this->config->getOpenTime();
        $data["makeup"] = $this->config->getMakeupPrice()->Ref_Desc;
        $data["ot_fee"] = $this->config->getConfig("OT_FEE","ANGEL_FEE");
        $data["ot_wage"] = $this->config->getConfig("OT_WAGE","ANGEL_FEE");
        $data["status"] = $status;
        $data["message"] = $message;
        return view("pages.systemsetting",$data);
    }
    function testprint(){

        return view("print.testprint");
    }
}
