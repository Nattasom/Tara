<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\RoomModel;
use App\CustomModel\EmployeeModel;
use App\CustomModel\UtilModel;
use App\CustomModel\TransactionModel;
use App\CustomModel\ConfigModel;
use App\CustomModel\Pagination;
use App\CustomModel\UserModel;

class Employee extends Controller
{
    private $employeeObj;
    private $roomObj;
    private $util;
    private $config;
    private $transObj;
    private $userObj;
    function __construct(){
        $this->employeeObj = new EmployeeModel();
        $this->userObj = new UserModel();
        $this->roomObj = new RoomModel();
        $this->util = new UtilModel();
        $this->transObj = new TransactionModel();
        $this->config = new ConfigModel();
    }

    //
    function reception(Request $request){
        $page_id = 7;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->employeeObj->getReceptCount("","","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $list = $this->employeeObj->getReceptList("","","","",$start,$perpage);
    $status = "";
        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $data["list"]=$list;
        $data["paging"] = $paging;
        $data["status"] = $status;
        $data["flag_edit"] = $flagEdit;
        return view("pages.reception",$data);
    }
    function ajaxRecept(Request $request){
        $page_id = 7;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->employeeObj->getReceptCount($request->input("receptcode"),$request->input("nickname"),$request->input("receptname"),$request->input("receptlastname"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $list = $this->employeeObj->getReceptList($request->input("receptcode"),$request->input("nickname"),$request->input("receptname"),$request->input("receptlastname"),$start,$perpage);

        return view("ajax.loadreceipt",["list"=>$list,
        "paging"=>$paging,"flag_edit"=>$flagEdit]);
    }
    function receptionadd(Request $request){
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "SysReception_Type_ID"=>$request->input("recept_type"),
                "Reception_ID"=>$request->input("recept_id"),
                "Employee_ID"=>$request->input("recept_code"),
                "First_Name"=>$request->input("fname"),
                "Last_Name"=>$request->input("lname"),
                "Nick_Name"=>$request->input("recept_nickname"),
                "Citizen_ID"=>$request->input("citizen_id"),
                "Address"=>$request->input("address"),
                "Tel_No"=>$request->input("phone"),
                "Delete_Flag"=>'N',
                "LastUpd_Dtm"=>date('Y-m-d H:i:s'),
                "LastUpd_User_ID"=>$uid,
                "CreateBy_USER_ID"=>$uid,
                "Create_Dtm"=>date('Y-m-d H:i:s'),
                "Work_From_Date"=>date('Y-m-d H:i:s'),
            );
            $result = $this->employeeObj->addReception($params);
            if($result>0){
                $request->session()->put("save_status","success");
                return redirect("/reception");
            }
        }


        $data["recept_type"] = $this->employeeObj->getReceptType();
        return view("pages.reception-add",$data);
    }
    function receptionedit(Request $request,$id){
        $status = "";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "id"=>$id,
                "SysReception_Type_ID"=>$request->input("recept_type"),
                "Reception_ID"=>$request->input("recept_id"),
                "Employee_ID"=>$request->input("recept_code"),
                "First_Name"=>$request->input("fname"),
                "Last_Name"=>$request->input("lname"),
                "Nick_Name"=>$request->input("recept_nickname"),
                "Citizen_ID"=>$request->input("citizen_id"),
                "Address"=>$request->input("address"),
                "Tel_No"=>$request->input("phone"),
                "LastUpd_Dtm"=>date('Y-m-d H:i:s'),
                "LastUpd_User_ID"=>$uid,
            );
            $result = $this->employeeObj->editReception($params);
            if($result>0){
                $status = "01";
            }
        }

        $recept = $this->employeeObj->getReception($id);
        if(is_null($recept)){
            return redirect("/reception");
        }
        $data["status"] = $status;
        $data["recept"] = $recept;
        $data["recept_type"] = $this->employeeObj->getReceptType();
        return view("pages.reception-edit",$data);
    }
    function receptdel(Request $request){
        $id = $request->input("del_id");
        $result = $this->employeeObj->delRecept($id);
        $request->session()->put("save_status","success_del");
        return redirect("/reception");
    }
    function ajaxReceiptPopup(Request $request){
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->employeeObj->getReceptCount("","","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $receiptlist = $this->employeeObj->getReceptList("","","","",$start,$perpage);

        return view("ajax.loadreceipt-popup",["receiptlist"=>$receiptlist,
        "paging"=>$paging]);
    }
    function ajaxPrettyPopup(Request $request){
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->employeeObj->getPrettyCount($request->input("empcode"),$request->input("nickname"),"","","",$request->input("status"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $prettylist = $this->employeeObj->getPrettyList($request->input("empcode"),$request->input("nickname"),"","","",$request->input("status"),$start,$perpage);

        return view("ajax.loadpretty-popup",["prettylist"=>$prettylist,
        "paging"=>$paging]);
    }
    function ajaxPrettyForReception(Request $request){
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->employeeObj->getPrettyWorkingCount($request->input("empcode"),$request->input("nickname"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $prettylist = $this->employeeObj->getPrettyWorkingList($request->input("empcode"),$request->input("nickname"),$start,$perpage);

        return view("ajax.loadpretty-reception",["prettylist"=>$prettylist,
        "paging"=>$paging]);
    }
    function ajaxPretty(Request $request){
        $page_id = 8;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->employeeObj->getPrettyCount($request->input("prcode"),$request->input("nickname"),$request->input("name"),$request->input("lastname"),$request->input("type"),$request->input("status"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $prettylist = $this->employeeObj->getPrettyList($request->input("prcode"),$request->input("nickname"),$request->input("name"),$request->input("lastname"),$request->input("type"),$request->input("status"),$start,$perpage);

        return view("ajax.loadpretty",["pretty_list"=>$prettylist,
        "paging"=>$paging,"util"=>$this->util,"flag_edit"=>$flagEdit]);
    }
    function prettytime(Request $request){
        $txndate = $this->transObj->getDateTxn();
        $type="";
        if($request->isMethod('post')){
            if(!is_null($request->input("datetrans"))){
                $txndate = $this->util->dateThaiToSystemFormat($request->input("datetrans"));
            }
            $type=$request->input("pretty_type_select");
        }
        $data["pretty_list"] = $this->employeeObj->getPrettyWorkingTable($txndate,"");
        $data["date"] = $this->util->dateToThaiFormat($txndate);
        $data["util"] = $this->util;
        $data["pretty_type"] = $this->employeeObj->getPrettyType($type);
        $data["selected_type"] = $type;
        $data["pretty_type_select"] = $this->employeeObj->getPrettyType();
        return view("pages.prettytime",$data);
    }
    function pretty(Request $request){
        $page_id = 8;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->employeeObj->getPrettyCount("","","","","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->employeeObj->getPrettyList("","","","","","",$start,$perpage);

        $status = "";
        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["pretty_list"] = $list;
        $data["paging"] = $paging;
        $data["pretty_status"] = $this->employeeObj->getPrettyStatus();
        $data["pretty_type"] = $this->employeeObj->getPrettyType();
        $data["flag_edit"] = $flagEdit;
        return view("pages.pretty",$data);
    }
    function prettyPay(Request $request){
        $page_id = 20;
        $cashier_id = $request->session()->get('userinfo')->SysUser_ID;
        $building_id = "";
        $flagB1 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_2");
        if(!($flagB1 && $flagB2)){
            if($flagB1){
                $building_id = "1";
            }
            if($flagB2){
                $building_id = "2";
            }

            if(!$flagB1 && !$flagB2){
                $building_id = "99";
            }
        }
        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->employeeObj->getPrettyWageCount("","","",$building_id);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->employeeObj->getPrettyWageList("","","",$building_id,$start,$perpage);

        $curpage1 = 1;
        $perpage1 = 10;
        $numpage1 = 0;
        $numrows1 = $this->employeeObj->getPrettyWageRecCount("","","",$building_id);
        $numpage1 = ceil($numrows1 / $perpage1);
        $start1 = ($curpage1 - 1) * $perpage1;

        $paging1 = new Pagination($curpage1,$perpage1,$numpage1,$numrows1,$start1);
        $list1 = $this->employeeObj->getPrettyWageRecList("","","",$building_id,$start1,$perpage1);

        $status = "";
        // if ($request->session()->exists('save_status')) {
        //     $status = $request->session()->get("save_status");
        //     $request->session()->forget('save_status');
        // }
        


        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["pretty_list"] = $list;
        $data["pretty_list1"] = $list1;
        $data["paging"] = $paging;
        $data["paging1"] = $paging1;
        return view("pages.prettypay",$data);
    }
    function ajaxPrettyPay(Request $request){
        $page_id = 20;
        $cashier_id = $request->session()->get('userinfo')->SysUser_ID;
        $building_id = "";
        $flagB1 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_2");
        if(!($flagB1 && $flagB2)){
            if($flagB1){
                $building_id = "1";
            }
            if($flagB2){
                $building_id = "2";
            }

            if(!$flagB1 && !$flagB2){
                $building_id = "99";
            }
        }

        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $sysdate = $this->util->dateThaiToSystemFormat($request->input("datetrans"));
        $numrows = $this->employeeObj->getPrettyWageCount($sysdate,$request->input("prcode"),$request->input("prname"),$building_id);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->employeeObj->getPrettyWageList($sysdate,$request->input("prcode"),$request->input("prname"),$building_id,$start,$perpage);

        $status = "";
        // if ($request->session()->exists('save_status')) {
        //     $status = $request->session()->get("save_status");
        //     $request->session()->forget('save_status');
        // }
        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["pretty_list"] = $list;
        $data["paging"] = $paging;
        return view("ajax.loadprettypay",$data);
    }

    function ajaxPrettyPayRec(Request $request){
        $page_id = 20;
        $cashier_id = $request->session()->get('userinfo')->SysUser_ID;
        $building_id = "";
        $flagB1 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_2");
        if(!($flagB1 && $flagB2)){
            if($flagB1){
                $building_id = "1";
            }
            if($flagB2){
                $building_id = "2";
            }

            if(!$flagB1 && !$flagB2){
                $building_id = "99";
            }
        }

        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $sysdate = $this->util->dateThaiToSystemFormat($request->input("datetrans"));
        $numrows = $this->employeeObj->getPrettyWageRecCount($sysdate,$request->input("prcode"),$request->input("prname"),$building_id);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging1 = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->employeeObj->getPrettyWageRecList($sysdate,$request->input("prcode"),$request->input("prname"),$building_id,$start,$perpage);

        $status = "";
        // if ($request->session()->exists('save_status')) {
        //     $status = $request->session()->get("save_status");
        //     $request->session()->forget('save_status');
        // }
        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["pretty_list1"] = $list;
        $data["paging1"] = $paging1;
        return view("ajax.loadprettypayrec",$data);
    }

    function prettyWage(Request $request,$id){
        $page_id = 20;
        $cashier_id = $request->session()->get('userinfo')->SysUser_ID;
        $building_id = "";
        $flagB1 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($cashier_id,$page_id,"EDIT_2");
        if(!($flagB1 && $flagB2)){
            if($flagB1){
                $building_id = "1";
            }
            if($flagB2){
                $building_id = "2";
            }

            if(!$flagB1 && !$flagB2){
                $building_id = "99";
            }
        }

        $status="";
        $txndate = $request->input("txndate");
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $perform = $this->employeeObj->getPrettyPerformance($id,$request->input("txndate"));
         $listwage = $this->employeeObj->getPrettyWagePerson($id,$request->input("txndate"),$building_id);
        if(count($listwage)==0){
            return redirect("/prettypay");
        }
        if($request->isMethod('post')){
            $income = is_null($request->input("other_income")) ? 0 : $request->input("other_income");
            $d1 = (is_null($request->input("pay_debt1")) ? 0:$request->input("pay_debt1"));
            $d2 = (is_null($request->input("pay_debt2")) ? 0:$request->input("pay_debt2"));
            $d3 = (is_null($request->input("pay_debt3")) ? 0:$request->input("pay_debt3"));
            $d4 = (is_null($request->input("pay_debt4")) ? 0:$request->input("pay_debt4"));
            $d5 = (is_null($request->input("pay_debt5")) ? 0:$request->input("pay_debt5"));
            $charge = is_null($request->input("other_debt")) ? 0 : $request->input("other_debt");
            $charge += $d1+$d2+$d3+$d4+$d5;
            $makeup = is_null($request->input("sum_make_up")) ? 0:$request->input("sum_make_up");
            //update tnd_massage_angel_list
            // $this->employeeObj->updatePrettyWage($id,$txndate);
            $this->employeeObj->updatePrettyWage($request->input("angel_list"));
            $hist_id = $this->employeeObj->keepHistoryWage($txndate,$id,$income,$request->input("other_income_remark"),$charge,$request->input("other_debt_remark"),$makeup,$request->input("net_amt"),$request->input("angel_list"),$request->input("ot_list"));
            if(is_null($perform)){
                 //insert performance
                $params = array(
                    "Txn_Date"=>$txndate,
                    "SysAngel_ID"=>$id,
                    "Total_Fee_Amt"=>$request->input("sum_wage"),
                    "Total_Wage_Amt"=>$request->input("sum_wage"),
                    "Total_Commission_Amt"=>$request->input("sum_comm"),
                    "Make_Up_Amt"=>is_null($request->input("sum_make_up")) ? 0:$request->input("sum_make_up"),
                    "Total_Round"=>$request->input("sum_round"),
                    "Total_Receive"=>$request->input("net_amt"),
                    "Other_Debt"=>is_null($request->input("other_debt")) ? 0 : $request->input("other_debt"),
                    "Other_Debt_Remark"=>$request->input("other_debt_remark"),
                    "Other_Income"=>is_null($request->input("other_income")) ? 0 : $request->input("other_income"),
                    "Other_Income_Remark"=>$request->input("other_income_remark"),
                    "Pay_Debt1"=> (is_null($request->input("pay_debt1")) ? 0:$request->input("pay_debt1")),
                    "Pay_Debt2"=>(is_null($request->input("pay_debt2")) ? 0:$request->input("pay_debt2")),
                    "Pay_Debt3"=>(is_null($request->input("pay_debt3")) ? 0:$request->input("pay_debt3")),
                    "Pay_Debt4"=>(is_null($request->input("pay_debt4")) ? 0:$request->input("pay_debt4")),
                    "Pay_Debt5"=>(is_null($request->input("pay_debt5")) ? 0:$request->input("pay_debt5")),
                    "LastUpd_User_ID"=>$uid,
                    "LastUpd_Dtm"=>date("Y-m-d H:i:s"),
                );
                $iid = $this->employeeObj->addPrettyPerformance($params);
                if($iid > 0){
                    if(!is_null($request->input("pay_debt1"))){
                        if(intval($request->input("pay_debt1")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt1",intval($request->input("pay_debt1")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt2"))){
                        if(intval($request->input("pay_debt2")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt2",intval($request->input("pay_debt2")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt3"))){
                        if(intval($request->input("pay_debt3")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt3",intval($request->input("pay_debt3")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt4"))){
                        if(intval($request->input("pay_debt4")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt4",intval($request->input("pay_debt4")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt5"))){
                        if(intval($request->input("pay_debt5")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt5",intval($request->input("pay_debt5")),$id);
                        }
                    }
                    $status = "01";
                    return redirect("/prettywage-success?id=".$hist_id);
                }
            }
            else{
                $params = array(
                    "Txn_Date"=>$txndate,
                    "SysAngel_ID"=>$id,
                    "Total_Fee_Amt"=>$request->input("sum_wage"),
                    "Total_Wage_Amt"=>$request->input("sum_wage"),
                    "Total_Commission_Amt"=>$request->input("sum_comm"),
                    "Make_Up_Amt"=>is_null($request->input("sum_make_up")) ? 0:$request->input("sum_make_up"),
                    "Total_Round"=>$request->input("sum_round"),
                    "Total_Receive"=>$request->input("net_amt"),
                    "Other_Debt"=>$request->input("other_debt"),
                    "Other_Debt_Remark"=>$request->input("other_debt_remark"),
                    "Other_Income"=>is_null($request->input("other_income")) ? 0 : $request->input("other_income"),
                    "Other_Income_Remark"=>$request->input("other_income_remark"),
                    "Pay_Debt1"=> (is_null($request->input("pay_debt1")) ? 0:$request->input("pay_debt1")),
                    "Pay_Debt2"=>(is_null($request->input("pay_debt2")) ? 0:$request->input("pay_debt2")),
                    "Pay_Debt3"=>(is_null($request->input("pay_debt3")) ? 0:$request->input("pay_debt3")),
                    "Pay_Debt4"=>(is_null($request->input("pay_debt4")) ? 0:$request->input("pay_debt4")),
                    "Pay_Debt5"=>(is_null($request->input("pay_debt5")) ? 0:$request->input("pay_debt5")),
                    "LastUpd_User_ID"=>$uid,
                    "LastUpd_Dtm"=>date("Y-m-d H:i:s"),

                );
                $iid = $this->employeeObj->updatePrettyPerformance($params);
                if($iid > 0){
                    if(!is_null($request->input("pay_debt1"))){
                        if(intval($request->input("pay_debt1")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt1",intval($request->input("pay_debt1")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt2"))){
                        if(intval($request->input("pay_debt2")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt2",intval($request->input("pay_debt2")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt3"))){
                        if(intval($request->input("pay_debt3")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt3",intval($request->input("pay_debt3")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt4"))){
                        if(intval($request->input("pay_debt4")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt4",intval($request->input("pay_debt4")),$id);
                        }
                    }
                    if(!is_null($request->input("pay_debt5"))){
                        if(intval($request->input("pay_debt5")) > 0){
                            $this->employeeObj->editPrettyDeductDebt("Debt5",intval($request->input("pay_debt5")),$id);
                        }
                    }
                    $status = "01";
                    return redirect("/prettywage-success?id=".$hist_id);
                }
            }
           
        }
        $pretty = $this->employeeObj->getPretty($id);
       
        $data["status"] = $status;
        $data["pretty"] = $pretty;
        $data["util"] = $this->util;
        $data["listwage"] = $listwage;
        $data["perform"] = $perform;
        $data["makeup"] = $this->config->getMakeupPrice();
        $data["txndate"] = $this->util->dateToThaiFormat($request->input("txndate"));
        return view("pages.pretty-wage",$data);
    }
    function prettyWageSuccess(Request $request){
        $id = $request->input("id");
        $angel_id = "";
        $txndate = "";
        $hist = $this->employeeObj->getHistoryWage($id);
        $angel_id = $hist->SysAngel_ID;
        $txndate = $hist->Txn_Date;
        $data["hist_id"] = $id;
        $data["aid"] = $angel_id;
        $data["txndate"] = $txndate;
        return view("pages.prettywage-complete",$data);
    }
    function prettyWageBill(Request $request){
        $hist_id = $request->input("id");
        $angel_id = $request->input("aid");
        $txndate = $request->input("txndate");
        $pretty = $this->employeeObj->getPretty($angel_id);
        // $listwage = $this->employeeObj->getPrettyWageRecPerson($angel_id,$request->input("txndate"));
        $hist = $this->employeeObj->getHistoryWage($hist_id);
        $data["pretty"] = $pretty;
        $data["util"] = $this->util;
        // $data["listwage"] = $listwage;
        $data["hist"] = $hist;
        $data["txndate"] = $this->util->dateToThaiFormat($txndate);
        $data["makeup"] = $this->config->getMakeupPrice();
        $data["perform"] = $this->employeeObj->getPrettyPerformance($angel_id,$request->input("txndate"));
        return view("print.prettywage-bill",$data);
    }
    function prettyadd(Request $request){
        $status = "";
        $message = "";
        $params = array(
                    "SysAngelType"=>"",
                    "Angel_ID"=>"",
                    "First_Name"=>"",
                    "Last_Name"=>"",
                    "Nick_Name"=>"",
                    "Citizen_ID"=>"",
                    "Tel_No"=>"",
                    "Part_Time_Flag"=>"",
                    "Debt1"=>"",
                    "Debt2"=>"",
                    "Debt3"=>"",
                    "Debt4"=>"",
                    "Debt5"=>"",
                );
        $rfid_card = "";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "SysAngelType"=>$request->input("type"),
                "Angel_ID"=>$request->input("emp_id"),
                "First_Name"=>$request->input("fname"),
                "Last_Name"=>$request->input("lname"),
                "Nick_Name"=>$request->input("nickname"),
                "Citizen_ID"=>$request->input("citizen"),
                "Tel_No"=>$request->input("phone"),
                "Angel_Status"=>'AB',
                "Work_From_Date"=>date("Y-m-d"),
                "Delete_Flag"=>'N',
                "LastUpd_Dtm"=>date("Y-m-d H:i:s"),
                "LastUpd_User_ID"=>$uid,
                "CreateBy_USER_ID"=>$uid,
                "Create_Dtm"=>date("Y-m-d H:i:s"),
                "Part_Time_Flag"=>$request->input("parttime"),
                "Debt1"=>$request->input("debt1"),
                "Debt2"=>$request->input("debt2"),
                "Debt3"=>$request->input("debt3"),
                "Debt4"=>$request->input("debt4"),
                "Debt5"=>$request->input("debt5"),
            );
            $rfid_card = $request->input("card_no");
            if(!$this->employeeObj->checkDuplicatePrettyCode($request->input("emp_id"))){
                do{
                    if(!empty($request->input("card_no"))){ //rfid check
                        if($this->employeeObj->checkDuplicatePrettyRFID($rfid_card)){
                            $status = "02";
                            $message = "เลขที่บัตรได้ถูกใช้ไปแล้ว";
                            break;
                        }
                    }
                    $result = $this->employeeObj->addPretty($params);
                    if($result>0){
                        if(!empty($request->input("card_no"))){ //rfid map
                            $rfid = array(
                                "SysRFID_ID"=>$request->input("card_no"),
                                "SysObject_ID"=>$result,
                                "Object_Type"=>'AN'
                            );
                            $this->employeeObj->addRfidCard($rfid);
                        }
                        $request->session()->put("save_status","success");
                        return redirect("/pretty#employee");
                    }
                }while(false);
                
            }
            else{
                $status = "02";
                $message = "รหัสพนักงานซ้ำ";
            }
            
        }


        $data["status"] = $status;
        $data["message"] = $message;
        $data["params"] = $params;
        $data["rfid_no"] = $rfid_card;
        $data["pretty_type"] = $this->employeeObj->getPrettyType();
        return view("pages.pretty-add",$data);
    }
    function prettytypeadd(Request $request){
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "Angel_Type_Code"=>$request->input("type_code"),
                "Angel_Type_Desc"=>$request->input("type_code"),
                "Angel_Fee"=>$request->input("type_fee"),
                "Angel_Wage"=>$request->input("type_wage"),
                "LastUpd_Dtm"=>date("Y-m-d H:i:s"),
                "SysAngel_Type_GR_ID"=>0,
                "LastUpd_User_ID"=>$uid,
                "Credit_Comm"=>empty($request->input("type_com")) ? 0:$request->input("type_com"),
                "round_time"=>$request->input("type_round"),
                "Delete_Flag"=>'N'
            );
            $result = $this->employeeObj->addPrettyType($params);
            if($result>0){
                $request->session()->put("save_status","success");
                return redirect("/pretty#emptype");
            }
        }
        return view("pages.prettytype-add");
    }
    function prettytypeedit(Request $request,$id){
        $status="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "Angel_Type_Code"=>$request->input("type_code"),
                "Angel_Type_Desc"=>$request->input("type_code"),
                "Angel_Fee"=>$request->input("type_fee"),
                "Angel_Wage"=>$request->input("type_wage"),
                "LastUpd_Dtm"=>date("Y-m-d H:i:s"),
                "LastUpd_User_ID"=>$uid,
                "Credit_Comm"=>empty($request->input("type_com")) ? 0:$request->input("type_com"),
                "round_time"=>$request->input("type_round"),
                "id"=>$id
            );
            $result = $this->employeeObj->editPrettyType($params);
            if($result>0){
                $status="01";
            }
        }
        $data["status"] = $status;
        $data["pretty_type"] = $this->employeeObj->getPrettyTypeByID($id);
        return view("pages.prettytype-edit",$data);
    }
    function prettyedit(Request $request,$id){
        $status="";
        $message = "";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "id"=>$id,
                "SysAngelType"=>$request->input("type"),
                "Angel_ID"=>$request->input("emp_id"),
                "First_Name"=>$request->input("fname"),
                "Last_Name"=>$request->input("lname"),
                "Nick_Name"=>$request->input("nickname"),
                "Citizen_ID"=>$request->input("citizen"),
                "Tel_No"=>$request->input("phone"),
                "LastUpd_Dtm"=>date("Y-m-d H:i:s"),
                "LastUpd_User_ID"=>$uid,
                "Part_Time_Flag"=>$request->input("parttime"),
                "Debt1"=>$request->input("debt1"),
                "Debt2"=>$request->input("debt2"),
                "Debt3"=>$request->input("debt3"),
                "Debt4"=>$request->input("debt4"),
                "Debt5"=>$request->input("debt5"),
                "Angel_Status"=>$request->input("ang_stat")
            );
            $rfid_card = $request->input("card_no");
            if(!$this->employeeObj->checkDuplicatePrettyCode($request->input("emp_id"),$id)){
                do{
                    if(!empty($request->input("card_no"))){ //rfid check
                        if($this->employeeObj->checkDuplicatePrettyRFID($rfid_card,$id)){
                            $status = "02";
                            $message = "เลขที่บัตรได้ถูกใช้ไปแล้ว";
                            break;
                        }
                    }
                    $result = $this->employeeObj->editPretty($params);
                    if($result>0){
                        $this->employeeObj->delRfidCard($id,"AN");
                        if(!empty($request->input("card_no"))){ //rfid map
                            $rfid = array(
                                "SysRFID_ID"=>$request->input("card_no"),
                                "SysObject_ID"=>$id,
                                "Object_Type"=>'AN'
                            );
                            $this->employeeObj->addRfidCard($rfid);
                        }
                        $status="01";
                    }
                }while(false);
                
            }else{
                $status = "02";
                $message = "รหัสพนักงานซ้ำ";
            }
            
        }

        $pretty = $this->employeeObj->getPretty($id);
        $card_no = "";
        $rfid = $this->employeeObj->getRfidCard($pretty->SysAngel_ID,"AN");
        if(is_null($pretty)){
            return redirect("/pretty#employee");
        }
        if(!is_null($rfid)){
            $card_no = $rfid->SysRFID_ID;
        }
        $data["status"] = $status;
        $data["message"] = $message;
        $data["card_no"] = $card_no;
        $data["pretty"] = $pretty;
        $data["pretty_type"] = $this->employeeObj->getPrettyType();
        return view("pages.pretty-edit",$data);
    }
    function prettydel(Request $request){
        $id = $request->input("del_id");
        $result = $this->employeeObj->delPretty($id);
        $request->session()->put("save_status","success_del");
        return redirect("/pretty#employee");
    }
    function prettytypedel(Request $request){
        $id = $request->input("del_id");
        $result = $this->employeeObj->delPrettyType($id);
        $request->session()->put("save_status","success_del");
        return redirect("/pretty#emptype");
    }
    function time(Request $request){
        return view("pages.time");
    }
    function ajaxtimestamp(Request $request){
        $response = array(
            "status"=>"00",
            "message"=>"ไม่พบข้อมูล"
        );
        //AN
        $card = $request->input("card_no");
        $type = $request->input("type");
        $date = $this->transObj->getDateTxn();
        $pr = $this->employeeObj->getPrettyByCard($card);
        if(!is_null($pr)){
            $name = $pr->Angel_ID.": ".$pr->Nick_Name;
            if($type=="IN"){
                $stamp = $this->employeeObj->stampTimeWork($date,$card,$type);
                if($stamp>0){
                    $this->employeeObj->updatePrettyStatus($pr->SysAngel_ID,"NW");
                    $response = array(
                        "status"=>"01",
                        "message"=>"พนักงาน ".$name." เข้างานเรียบร้อย"
                    );
                }
            }else{
                $stamp = $this->employeeObj->stampTimeWork($date,$card,$type);
                if($stamp>0){
                    $this->employeeObj->updatePrettyStatus($pr->SysAngel_ID,"AB");
                    $response = array(
                        "status"=>"01",
                        "message"=>"พนักงาน ".$name." ออกงานเรียบร้อย"
                    );
                }
            }
        }
        

        return response()->json($response);
    }
    function ajaxGetPrettybyCard(Request $request){
        $response = array(
            "status"=>"00",
            "message"=>"พนักงานไม่พร้อมทำงาน"
        );
        $card = $request->input("card");
        $pr = $this->employeeObj->getPrettyByCard($card);
        if(!is_null($pr)){
             $response = array(
                "status"=>"01",
                "message"=>"",
                "id"=>$pr->SysAngel_ID,
                "code"=>$pr->Angel_ID,
                "name"=>$pr->Nick_Name,
                "angel_status"=>$pr->Angel_Status
            );
        }
        return response()->json($response);
    }
}
