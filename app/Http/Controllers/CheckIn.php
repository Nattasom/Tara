<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\RoomModel;
use App\CustomModel\UtilModel;
use App\CustomModel\EmployeeModel;
use App\CustomModel\TransactionModel;
use App\CustomModel\UserModel;

class CheckIn extends Controller
{
    private $roomObj;
    private $transObj;
    private $util;
    private $employeeObj;
    private $userObj;
    function __construct(){
        $this->roomObj = new RoomModel();
        $this->userObj = new UserModel();
        $this->transObj = new TransactionModel();
        $this->util = new UtilModel();
        $this->employeeObj = new EmployeeModel();
    }
    function index(Request $request){
        $page_id = 18;
        $cashier_id = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $recept_id = $request->input("recept_id");
            $detail_checkin = $request->input("room_checkin");
            $date_trans = $this->transObj->getDateTxn();
            $txn_no = $this->transObj->getLastTxnNo($date_trans);
            $arrRooms = explode('|',$detail_checkin);
            $flagError = false;
            //txn checkin
            $resTxn = $this->transObj->Checkin($txn_no,$date_trans,$recept_id,$cashier_id);
                
            foreach($arrRooms as $kroom => $vroom){ // room loop
                $arrPretty = explode(';',$vroom); //angel
                $roomID_wflag = explode('=',$arrPretty[0]);
                $roomID = $roomID_wflag[0];
                $wFlag = $roomID_wflag[1];
                
                //room checkin
                $resRoom = $this->transObj->roomCheckIn($txn_no,$date_trans,$roomID,$recept_id,$wFlag);
                if($resRoom < 1){ // fail
                    $flagError = true;
                }
                $prettys = explode(',',$arrPretty[1]);
                foreach($prettys as $kp=>$vp){
                    if(empty($vp)){
                        continue;
                    }
                    $resPretty = $this->transObj->prettyCheckIn($txn_no,$date_trans,$roomID,$vp,$recept_id);
                    if($resPretty < 1){ // fail
                        $flagError = true;
                    }
                }
                
            }
            if(!$flagError){
                 $request->session()->put('last_chkin_building', $request->input("building"));
                return redirect("/checkin/complete?txn=".$txn_no."&txndate=".$date_trans);
            }

        }
        $selected_building = "";
        if($request->session()->has('last_chkin_building'))
        {
            $selected_building = $request->session()->get("last_chkin_building");
        }
        $flagB1 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_2");
        $b_flag["B_1"] = $flagB1;
        $b_flag["B_2"] = $flagB2;
        $data["B_flag"] = $b_flag;
        $data["selected_building"] = $selected_building;
        $data["receptlist"] = $this->employeeObj->getReceptList("","","","",0,100);
        $data["buildinglist"] = $this->roomObj->getBuildingList();
        return view("pages.checkin",$data);
    }
    function edit(Request $request){
        if(is_null($request->input("txn")) || is_null($request->input("txndate"))){
            return redirect("/checkoutlist");
        }
        $txn = $request->input("txn");
        $date =$request->input("txndate"); 
        $cashier_id = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $checkin_time = "";
            $rooms = $this->transObj->getRoomCheckin($txn,$date);
            if(count($rooms)>0){
                if(!is_null($rooms[0]->Check_In_Time)){
                    $checkin_time = $rooms[0]->Check_In_Time;
                }
            }
            
            $prettylist = $this->transObj->getPrettyCheckin($txn,$date,"T");
            $resClear = $this->transObj->clearRoomAndPretty($txn,$date,"T");
            if($resClear > 0){
                $recept_id = $request->input("recept_id");
                $detail_checkin = $request->input("room_checkin");
                $arrRooms = explode('|',$detail_checkin);
                $flagError = false;
                //txn checkin
                $resTxn = $this->transObj->Checkin($txn,$date,$recept_id,$cashier_id);
                    $checkin_time_pr = "";
                    $checkin_round = 1;
                    $checkout_time_pr="";
                $count_new_print = 0;
                $str_new_print = "";
                foreach($arrRooms as $kroom => $vroom){ // room loop
                    
                    $arrPretty = explode(';',$vroom); //angel
                    $roomID_wflag = explode('=',$arrPretty[0]);
                    $roomID = $roomID_wflag[0];
                    $wFlag = $roomID_wflag[1];
                    
                    //room checkin
                    $resRoom = $this->transObj->roomCheckIn($txn,$date,$roomID,$recept_id,$wFlag,$checkin_time);
                    if($resRoom < 1){ // fail
                        $flagError = true;
                    }
                    $prettys = explode(',',$arrPretty[1]);
                    
                    foreach($prettys as $kp=>$vp){
                        if(empty($vp)){
                            continue;
                        }
                        foreach($prettylist as $kc=>$vc){
                            if($vc->SysAngel_ID==$vp){
                                $checkin_time_pr = $vc->Check_In_Time;
                                $checkin_round = $vc->Round;
                                $checkout_time_pr = $vc->Check_Out_Time;
                            }
                        }
                        $resPretty = $this->transObj->prettyCheckIn($txn,$date,$roomID,$vp,$recept_id,$checkin_time_pr,$checkin_round,$checkout_time_pr);
                        if(empty($checkin_time_pr)){
                            if($count_new_print != 0){
                                $str_new_print .= ",";
                            }
                            $str_new_print .= $resPretty;
                            $count_new_print++;
                        }
                        if($resPretty < 1){ // fail
                            $flagError = true;
                        }
                        $checkin_time_pr = "";
                        $checkin_round = 1;
                        $checkout_time_pr = "";
                    }
                    
                }
                if(!$flagError){
                    return redirect("/checkin/complete?txn=".$txn."&txndate=".$date."&newprint=".$str_new_print);
                }
            }
        }
        $rooms = $this->transObj->getRoomCheckin($txn,$date);
        $pretty = $this->transObj->getPrettyCheckinGroupbyRoom($txn,$date,"T");
        $prettylist = $this->transObj->getPrettyCheckin($txn,$date,"T");
        
        $prettymap = "";
        $building = "";
        $recept_id = "";
        $recept_code = "";
        $recept_name ="";
        $roommap = "";
        if(count($rooms)>0){
            $building = $rooms[0]->building_id;
            $recept_id = $rooms[0]->SysReception_ID;
            $recept_code = $rooms[0]->Reception_ID;
            $recept_name = $rooms[0]->First_Name." ".$rooms[0]->Last_Name;
            $i=0;
            foreach($rooms as $key=>$value){
                if($i!=0){
                    $roommap .=",";
                }
                $roommap .= $value->SysRoom_ID;
                $i++;
            }
        }
        if(count($prettylist)>0){
             $i=0;
            foreach($prettylist as $key=>$value){
                if($i!=0){
                    $prettymap .=",";
                }
                $prettymap .= $value->SysAngel_ID;
                $i++;
            }
        }
        $data["pretty"] = $pretty;
        $data["prettymap"] = $prettymap;
        $data["roommap"] = $roommap;
        $data["roomlist"] = $rooms;
        $data["building"] = $building;
        $data["recept_id"] = $recept_id;
        $data["recept_code"] = $recept_code;
        $data["recept_name"] = $recept_name;
        $data["receptlist"] = $this->employeeObj->getReceptList("","","","",0,100);
        $data["buildinglist"] = $this->roomObj->getBuildingList();
        return view("pages.checkin-edit",$data);
    }
    function checkinlist(){
        return view("pages.checkinlist");
    }
    function ajaxLoadRoom(Request $request){
        $building_id = $request->input("building_id");
        $regular = $this->roomObj->getRegularRoom($building_id);
        $vip = $this->roomObj->getVIPRoom($building_id);
        $suite = $this->roomObj->getSuiteRoom($building_id);
        $subsuite = $this->roomObj->getSubSuiteRoomByBuilding($building_id);

        $data["regular"] = $regular;
        $data["vip"] = $vip;
        $data["suite"] = $suite;
        $data["subsuite"] = $subsuite;
        return view("ajax.loadcheckinroom",$data);
    }
    function ajaxLoadSubSuiteJson(Request $request){
        $response = array();
        if(!is_null($request->input("id"))){
            $subroom = $this->roomObj->getSubSuiteRoom($request->input("id"));
            foreach($subroom as $key=>$value){
                $response[] = array(
                    "SysRoom_ID"=>$value->SysRoom_ID,
                    "Room_No"=>$value->Room_No,
                    "Room_Type_Desc"=>$value->Room_Type_Desc,
                );
            }
        }
        return response()->json($response);
    }
    function complete(Request $request){
        $txn = "";
        $txndate = $request->input("txndate");
        if(!is_null($request->input("txn"))){
            $txn = $request->input("txn");
        }
        if(!is_null($request->input("txndate"))){
            $txndate = $request->input("txndate");
        }
        $pr_list = $this->transObj->getPrettyListByTxn($txn,$txndate);
        if(!is_null($request->input("newprint"))){
            $arrNew = explode(',',$request->input("newprint"));
            if(count($arrNew) > 0){
                $pr_list = array();
                foreach($arrNew as $key=>$value){
                    $tmp = new \stdClass();
                    $tmp->SysMassage_Angel_List_ID = $value;
                    $pr_list[] = $tmp;
                }
            }
        }
        $data["list"] = $pr_list;
        return view("pages.checkin-complete",$data);
    }
    function roombill(Request $request){
        $id = $request->input("id");
        $cashier = $request->session()->get('userinfo')->User_Fullname;
        $data["pr"] = $this->transObj->getPrettyByID($id);
        $data["util"] = $this->util;
        $data["cashier"] = $cashier;
        return view("print.roombill",$data);
    }
}
