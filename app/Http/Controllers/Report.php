<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\RoomModel;
use App\CustomModel\EmployeeModel;
use App\CustomModel\MemberModel;
use App\CustomModel\TransactionModel;
use App\CustomModel\UtilModel;
use App\CustomModel\UserModel;
class Report extends Controller
{
    private $memberObj;
    private $transObj;
    private $util;
    private $roomObj;
    private $employeeObj;
    private $userObj;
    function __construct(){
        $this->memberObj = new MemberModel();
        $this->transObj = new TransactionModel();
        $this->roomObj = new RoomModel();
        $this->employeeObj = new EmployeeModel();
        $this->util = new UtilModel();
        $this->userObj = new UserModel();
    }
    //
    function daily(Request $request){
        $page_id = 19;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagB1 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_2");
        $bb = array();
        $bb["1"] = $flagB1;
        $bb["2"] = $flagB2;
        $data["flag_building"] = $bb;
        $data["buildinglist"] = $this->roomObj->getBuildingList();
        $data["date"] = $this->util->dateToThaiFormat(date("Y-m-d"));
        return view('pages.daily-report',$data);
    }
    function dailyPrint(Request $request){
        $data["prlist"] = $this->transObj->dailyReport($this->util->dateThaiToSystemFormat($request->input("date")),$request->input("building"));
        $data["payment"] = $this->transObj->dailyPayReport($this->util->dateThaiToSystemFormat($request->input("date")),$request->input("building"));
        $data["date"] = $request->input("date");
        $btext = "";
        if(empty($request->input("building"))){
            $btext = "ทั้งหมด";
        }
        else{
            $btext = $request->input("building")=="1" ? "TG":"TA";
        }
        $data["building"] = $btext;
        $data["util"] = $this->util;
        return view('print.daily-report',$data);
    }
    function statusall(Request $request){

        $selected_building = "";
        if($request->session()->has('last_chkin_building'))
        {
            $selected_building = $request->session()->get("last_chkin_building");
        }
        $workingAll = $this->employeeObj->getPrettyWorkingCount("","");
        $data["working_all"] = $workingAll;
        $data["selected_building"] = $selected_building;
        $data["buildinglist"] = $this->roomObj->getBuildingList();
        $data["util"] = $this->util;
        return view("pages.statusall",$data);
    }
    function membersale(){

        return view('pages.membersale-report');
    }   
    function membersalePrint(Request $request){
        $start = $request->input("start");
        $end = $request->input("end");
        $list = $this->memberObj->getMemberSellList($start,$end);

        $data["start"] = empty($start) ? "-" : $start;
        $data["end"] = empty($end) ? "-" : $end;
        $data["list"] = $list;
        $data["util"] = $this->util;
        return view('print.member-sale',$data);
    }
    function memberuse(Request $request){
        $page_id = 19;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagB1 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_2");
        $bb = array();
        $bb["1"] = $flagB1;
        $bb["2"] = $flagB2;
        $data["flag_building"] = $bb;
        $data["buildinglist"] = $this->roomObj->getBuildingList();
        return view('pages.memberuse-report',$data);
    }  
    function memberusePrint(Request $request){
        $start = $request->input("start");
        $end = $request->input("end");
        $list = $this->memberObj->getMemberUseList($start,$end,$request->input("building"));

        $data["start"] = empty($start) ? "-" : $start;
        $data["end"] = empty($end) ? "-" : $end;
        $data["list"] = $list;
        $data["util"] = $this->util;
        return view('print.member-use',$data);
    } 
    function receptMonthly(){
        
        $month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $year = array();
        for($y= intval(date("Y"));$y > (intval(date("Y"))-3);$y--){
            $year[] = $y+543;
        }
        $data["month"] = $month;
        $data["year"] = $year;
        $data["cur_month"] = date("m");
        $data["cur_year"] = intval(date("Y"))+543;
        return view('pages.recept-monthly-report',$data);
    }
    function receptMonthlyPrint(Request $request){
        $r_month = $request->input("month");
        $r_year = $request->input("year");
        $month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $startDate = ($r_year-543)."-".str_pad($r_month,2,'0',STR_PAD_LEFT)."-01";
        $endOfMonth = date("Y-m-t", strtotime($startDate));
        $start = strtotime($startDate);
        $end = strtotime($endOfMonth);
        $loopDay = array();
        while($start <= $end)
        {
            $day = date("d",$start);
            $format = date("Y-m-d",$start);
            $weekday = date("w",$start);
            $loopDay[$day] = array("date"=>$format,"weekday"=>$weekday);
            $start = strtotime("+1 day", $start);
        }
        $pr_list = $this->transObj->receptMonthlyReport($r_month,($r_year-543));
        $data["pr_list"] = $pr_list;
        $data["col_day"] = $loopDay;
        $data["month"]=$month[$r_month-1];
        $data["year"] = $r_year;
        $data["util"] = $this->util;
        return view('print.recept-monthly-report',$data);
    }
    function prettyMonthly(){
        
        $month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $year = array();
        for($y= intval(date("Y"));$y > (intval(date("Y"))-3);$y--){
            $year[] = $y+543;
        }
        $data["month"] = $month;
        $data["year"] = $year;
        $data["cur_month"] = date("m");
        $data["cur_year"] = intval(date("Y"))+543;
        return view('pages.pr-monthly-report',$data);
    }
    function prettyMonthlyPrint(Request $request){
        $r_month = $request->input("month");
        $r_year = $request->input("year");
        $month = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $startDate = ($r_year-543)."-".str_pad($r_month,2,'0',STR_PAD_LEFT)."-01";
        $endOfMonth = date("Y-m-t", strtotime($startDate));
        $start = strtotime($startDate);
        $end = strtotime($endOfMonth);
        $loopDay = array();
        while($start <= $end)
        {
            $day = date("d",$start);
            $format = date("Y-m-d",$start);
            $weekday = date("w",$start);
            $loopDay[$day] = array("date"=>$format,"weekday"=>$weekday);
            $start = strtotime("+1 day", $start);
        }
        $pr_list = $this->transObj->prettyMonthlyReport($r_month,($r_year-543));
        $data["pr_list"] = $pr_list;
        $data["col_day"] = $loopDay;
        $data["month"]=$month[$r_month-1];
        $data["year"] = $r_year;
        $data["util"] = $this->util;
        return view('print.pr-monthly-report',$data);
    }
    function receptionwork(Request $request){

        return view("pages.receptwork-report");
    }
    function receptionworkPrint(Request $request){
        $start = $request->input("start");
        $end = $request->input("end");
        $rc_id = $request->input("recept");
        $rec = $this->employeeObj->getReception($rc_id);
        $sysStartDate  = "";
        $sysEndDate = "";
        if(!empty($start)){
         $sysStartDate =    $this->util->dateThaiToSystemFormat($start);
        }
        if(!empty($end)){
            $sysEndDate = $this->util->dateThaiToSystemFormat($end);
        }
        $list = $this->employeeObj->getReceptionWork($sysStartDate,$sysEndDate,$rc_id);

        $data["start"] = empty($start) ? "-" : $start;
        $data["end"] = empty($end) ? "-" : $end;
        $data["receptname"] = $rec->First_Name." ".$rec->Last_Name."(".$rec->Nick_Name.")";
        $data["list"] = $list;
        $data["util"] = $this->util;
        return view("print.receptionwork",$data);
    }
}
