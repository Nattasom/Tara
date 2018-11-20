<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\MemberModel;
use App\CustomModel\UtilModel;
use App\CustomModel\Pagination;
use App\CustomModel\UserModel;
use App\CustomModel\TransactionModel;

class Member extends Controller
{
    private $memberObj;
    private $util;
    private $userObj;
    private $transObj;
    function __construct(){
        $this->userObj = new UserModel();
        $this->memberObj = new MemberModel();
        $this->util = new UtilModel();
        $this->transObj = new TransactionModel();
    }

    //
    function index(Request $request){
        $page_id = 4;
        $page_id_add = 3;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");
        $flagAdd = $this->userObj->checkActionUser($uid,$page_id_add,"VIEW");

        $status="";
        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->memberObj->getCount("","","","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $memberlist = $this->memberObj->getMemberList("","","","","",$start,$perpage);

        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $monthlist = array(
            1 => "มกราคม",
            2 => "กุมภาพันธ์",
            3 => "มีนาคม",
            4 => "เมษายน",
            5 => "พฤษภาคม",
            6 => "มิถุนายน",
            7 => "กรกฎาคม",
            8 => "สิงหาคม",
            9 => "กันยายน",
            10 => "ตุลาคม",
            11 => "พฤศจิกายน",
            12 => "ธันวาคม",
        );
        $curyear = intval(date("Y"));
        $yearlist = array();
        for($i=($curyear-3);$i<=($curyear+3);$i++){
            $yearlist[$i] = $i+543;
        }
        $data["member_status"] = $this->memberObj->getMemberStatus();
        $data["status"] = $status;
        $data["memberlist"] = $memberlist;
        $data["month"] = $monthlist;
        $data["yearlist"] = $yearlist;
        $data["util"]=$this->util;
        $data["paging"] = $paging;
        $data["flag_edit"] = $flagEdit;
        $data["flag_add"] = $flagAdd;
        return view("pages.memberlist",$data);
    }
    function memberSync(Request $request){ 
        $message = "";
        if($request->isMethod('post')){
            $this->memberObj->memberSync("Member_A_");
            $this->memberObj->memberSync("Member_B_");
            $this->memberObj->memberSync("Member_C_");
        }
        $data["message"] = $message;
        return view("pages.member-sync",$data);
    }
    function memberPay(Request $request){
        $status="";
        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->memberObj->getMemberPayCount("","","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $memberlist = $this->memberObj->getMemberPayList("","","","",$start,$perpage);

        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $data["status"] = $status;
        $data["memberlist"] = $memberlist;
        $data["util"]=$this->util;
        $data["paging"] = $paging;
        return view("pages.memberpaylist",$data);
    }
    function ajaxLoadMemberPay(Request $request){
        $status="";
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $dateTrans="";
        if(!empty($request->input("datetrans"))){
            $dateTrans = $this->util->dateThaiToSystemFormat($request->input("datetrans"));
        }
        $numrows = $this->memberObj->getMemberPayCount($request->input("member_no"),$request->input("member_name"),$request->input("member_lastname"),$dateTrans);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $memberlist = $this->memberObj->getMemberPayList($request->input("member_no"),$request->input("member_name"),$request->input("member_lastname"),$dateTrans,$start,$perpage);

        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $data["memberlist"] = $memberlist;
        $data["util"]=$this->util;
        $data["paging"] = $paging;
        return view("ajax.memberpaylist",$data);
    }
    function ajaxLoadMember(Request $request){
        $page_id = 4;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $status="";
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->memberObj->getCount($request->input("member_no"),$request->input("member_name"),$request->input("member_lastname"),$request->input("recept_id"),$request->input("member_status"),$request->input("member_month"),$request->input("member_year"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $memberlist = $this->memberObj->getMemberList($request->input("member_no"),$request->input("member_name"),$request->input("member_lastname"),$request->input("recept_id"),$request->input("member_status"),$start,$perpage,$request->input("member_month"),$request->input("member_year"));

        $data["status"] = $status;
        $data["memberlist"] = $memberlist;
        $data["util"]=$this->util;
        $data["paging"] = $paging;
        $data["flag_edit"] = $flagEdit;
        return view("ajax.memberlist",$data);
    }
    public function memberDebtPay(Request $request,$id){
        $status="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $date_trans = $this->transObj->getDateTxn();
            $updateMem = array(
                "Cash_Amt"=>(empty($request->input("member_cash")) ? 0:$request->input("member_cash")),
                "Credit_Amt"=>(empty($request->input("member_credit")) ? 0:$request->input("member_credit")),
                "Paid_Date"=>$date_trans,
                "uid"=>$uid,
                "id" => $id
            );
            $res = $this->memberObj->editMemberApplication($updateMem);
            if($res>0){
                $status="01";
                return redirect("/member/paysuccess?id=".$id);
            }
        }
        $mem_app = $this->memberObj->getMemberApplication($id);
        if(is_null($mem_app)){
            return redirect("/memberdebt");
        }

        $member = $this->memberObj->getMember($mem_app->SysMember_ID);
        if(is_null($member)){
            return redirect("/member");
        }


        $data["member_status"] = $this->memberObj->getMemberStatus();
        $data["member_type"] =$this->memberObj->getMemberType();
        $data["util"] = $this->util;
        $data["status"] = $status;
        $data["member_app"] = $mem_app;
        $data["member"] = $member;
        return view("pages.memberdebt-pay",$data);
    }
    function memberDebt(Request $request){
        $page_id = 6;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $status="";
        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->memberObj->getMemberDebtCount("","","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $memberlist = $this->memberObj->getMemberDebtList("","","","",$start,$perpage);

        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $data["status"] = $status;
        $data["memberlist"] = $memberlist;
        $data["util"]=$this->util;
        $data["paging"] = $paging;
        $data["flag_edit"] = $flagEdit;
        return view("pages.memberdebtlist",$data);
    }
    function ajaxLoadMemberDebt(Request $request){
        $page_id = 6;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");
        $status="";
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $dateTrans="";
        if(!empty($request->input("datetrans"))){
            $dateTrans = $this->util->dateThaiToSystemFormat($request->input("datetrans"));
        }
        $numrows = $this->memberObj->getMemberDebtCount($request->input("member_no"),$request->input("member_name"),$request->input("member_lastname"),$dateTrans);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $memberlist = $this->memberObj->getMemberDebtList($request->input("member_no"),$request->input("member_name"),$request->input("member_lastname"),$dateTrans,$start,$perpage);

        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $data["memberlist"] = $memberlist;
        $data["util"]=$this->util;
        $data["paging"] = $paging;
        $data["flag_edit"] = $flagEdit;
        return view("ajax.memberdebtlist",$data);
    }
    function ajaxLoadmemberPopup(Request $request){
        $status="";
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->memberObj->getCount($request->input("member_no"),$request->input("member_name"),"","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $memberlist = $this->memberObj->getMemberList($request->input("member_no"),$request->input("member_name"),"","","AV",$start,$perpage);

        $data["status"] = $status;
        $data["memberlist"] = $memberlist;
        $data["util"]=$this->util;
        $data["paging"] = $paging;
        return view("ajax.loadmember-popup",$data);
    }
    
    function add(Request $request){
        $status="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $date_trans = $this->transObj->getDateTxn();
        if($request->isMethod('post')){
            $params = array(
                "Member_ID"=>$request->input("member_no"),
                "First_Name"=>$request->input("member_name"),
                "Last_Name"=>$request->input("member_lastname"),
                "Address"=>$request->input("member_addr"),
                "Tel_No"=>$request->input("member_tel"),
                "SysReception_ID"=>$request->input("recept_id"),
                "Credit_Amt"=>$request->input("member_price"),
                "Whisky"=>(empty($request->input("whisky")) ? 0:$request->input("whisky")),
                "Whisky2"=>(empty($request->input("whisky2")) ? 0:$request->input("whisky2")),
                // "Beer"=>(empty($request->input("beer")) ? 0:$request->input("beer")),
                "Suite"=>(empty($request->input("member_suite")) ? 0:$request->input("member_suite")),
                "Suite2"=>(empty($request->input("member_suite2")) ? 0:$request->input("member_suite2")),
                "Suite3"=>(empty($request->input("member_suite3")) ? 0:$request->input("member_suite3")),
                "Expired_Date"=>date('Y-m-d',strtotime($date_trans . " + 365 day")),
                "Member_Status"=>'AV',
                "Member_From_Date"=>$date_trans,
                "Delete_Flag"=>'N',
                "LastUpd_Dtm"=>date('Y-m-d H:i:s'),
                "LastUpd_User_ID"=>$uid,
                "Create_Dtm"=>date('Y-m-d'),
                "CreateBy_USER_ID"=>$uid
            );
            $res = $this->memberObj->addMember($params);
            if($res>0){
                
                //add Application
                $insApp = array(
                    "SysMember_ID"=>$res,
                    "Cash_Amt"=>(empty($request->input("member_cash")) ? 0:$request->input("member_cash")),
                    "Credit_Amt"=>(empty($request->input("member_credit")) ? 0:$request->input("member_credit")),
                    "CreateBy_USER_ID"=>$uid,
                    "Txn_Date"=>$date_trans,
                    "Create_Dtm"=>date('Y-m-d'),
                    "SysReception_ID"=>$request->input("recept_id"),
                    "Unpaid_Amt"=>(empty($request->input("member_debt")) ? 0:$request->input("member_debt")),
                    "Add_Credit"=>$request->input("member_price"),
                    "Add_Suite"=>(empty($request->input("member_suite")) ? 0:$request->input("member_suite")),
                    "Add_Suite2"=>(empty($request->input("member_suite2")) ? 0:$request->input("member_suite2")),
                    "Add_Suite3"=>(empty($request->input("member_suite3")) ? 0:$request->input("member_suite3")),
                    "Add_Whisky"=>(empty($request->input("whisky")) ? 0:$request->input("whisky")),
                    "Add_Whisky2"=>(empty($request->input("whisky2")) ? 0:$request->input("whisky2")),
                    // "Add_Beer"=>(empty($request->input("beer")) ? 0:$request->input("beer")),
                    "LastUpd_User_ID"=>$uid
                );
                if(empty($request->input("member_debt"))){
                    $insApp["Paid_Date"]=$date_trans;
                }
                $app_id = $this->memberObj->addMemberApplication($insApp);
                $status="01";
                return redirect("/member/paysuccess?id=".$app_id);
            }
        }


        $data["member_type"] =$this->memberObj->getMemberType();
        $data["util"] = $this->util;
        $data["status"] = $status;
        return view("pages.member-add",$data);
    }
    function edit(Request $request,$id){
        $status="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $datefrom = empty($this->util->dateThaiToSystemFormat($request->input("datefrom"))) ? null:$this->util->dateThaiToSystemFormat($request->input("datefrom"));
            $dateexpired = empty($this->util->dateThaiToSystemFormat($request->input("dateexpired"))) ? null:$this->util->dateThaiToSystemFormat($request->input("dateexpired"));
            if(!is_null($request->input("chk_no_expired"))){
                $dateexpired = null;
            }
            $params = array(
                "id"=>$id,
                "Member_ID"=>$request->input("member_no"),
                "First_Name"=>$request->input("member_name"),
                "Last_Name"=>$request->input("member_lastname"),
                "Address"=>$request->input("member_addr"),
                "Tel_No"=>$request->input("member_tel"),
                "SysReception_ID"=>$request->input("recept_id"),
                "Member_From_Date"=>$datefrom,
                "Expired_Date"=>$dateexpired,
                "Member_Status"=>$request->input("member_status"),
                "LastUpd_Dtm"=>date('Y-m-d H:i:s'),
                "LastUpd_User_ID"=>$uid
            );
            $res = $this->memberObj->editMemberData($params);
            if($res>0){
                $status="01";
                
            }
        }
        $member = $this->memberObj->getMember($id);
        if(is_null($member)){
            return redirect("/member");
        }


        $data["member_status"] = $this->memberObj->getMemberStatus();
        $data["member_type"] =$this->memberObj->getMemberType();
        $data["util"] = $this->util;
        $data["status"] = $status;
        $data["member"] = $member;
        return view("pages.member-edit",$data);
    }
    public function topup(Request $request,$id){
        $status="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $date_trans = $this->transObj->getDateTxn();
        if($request->isMethod('post')){
            //add Application
            $insApp = array(
                "SysMember_ID"=>$id,
                "Cash_Amt"=>(empty($request->input("member_cash")) ? 0:$request->input("member_cash")),
                "Credit_Amt"=>(empty($request->input("member_credit")) ? 0:$request->input("member_credit")),
                "CreateBy_USER_ID"=>$uid,
                "Txn_Date"=>$date_trans,
                "Create_Dtm"=>date('Y-m-d'),
                "SysReception_ID"=>$request->input("recept_id"),
                "Unpaid_Amt"=>(empty($request->input("member_debt")) ? 0:$request->input("member_debt")),
                "Add_Credit"=>$request->input("member_price"),
                "Add_Suite"=>(empty($request->input("member_suite")) ? 0:$request->input("member_suite")),
                "Add_Suite2"=>(empty($request->input("member_suite2")) ? 0:$request->input("member_suite2")),
                "Add_Suite3"=>(empty($request->input("member_suite3")) ? 0:$request->input("member_suite3")),
                "Add_Whisky"=>(empty($request->input("whisky")) ? 0:$request->input("whisky")),
                "Add_Whisky2"=>(empty($request->input("whisky2")) ? 0:$request->input("whisky2")),
                // "Add_Beer"=>(empty($request->input("beer")) ? 0:$request->input("beer")),
                "LastUpd_User_ID"=>$uid
            );
            if(empty($request->input("member_debt"))){
                $insApp["Paid_Date"]=$date_trans;
            }
            $app_id = $this->memberObj->addMemberApplication($insApp);
            if($app_id > 0){
                $updateMem = array(
                    "Credit_Amt" => $request->input("member_price"),
                    "Whisky" => (empty($request->input("whisky")) ? 0:$request->input("whisky")),
                    "Whisky2" => (empty($request->input("whisky2")) ? 0:$request->input("whisky2")),
                    // "Beer" => (empty($request->input("beer")) ? 0:$request->input("beer")),
                    "Suite" => (empty($request->input("member_suite")) ? 0:$request->input("member_suite")),
                    "Suite2" => (empty($request->input("member_suite2")) ? 0:$request->input("member_suite2")),
                    "Suite3" => (empty($request->input("member_suite3")) ? 0:$request->input("member_suite3")),
                    "id" => $id
                );
                $this->memberObj->topupMember($updateMem);
                $status="01";
                return redirect("/member/paysuccess?id=".$app_id);
            }
            
        }
        $member = $this->memberObj->getMember($id);
        if(is_null($member)){
            return redirect("/member");
        }


        $data["member_status"] = $this->memberObj->getMemberStatus();
        $data["member_type"] =$this->memberObj->getMemberType();
        $data["util"] = $this->util;
        $data["status"] = $status;
        $data["member"] = $member;
        return view("pages.member-topup",$data);
    }
    public function deduct(Request $request,$id){
        $status="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $updateMem = array(
                "Whisky" => (empty($request->input("whisky")) ? 0:$request->input("whisky")),
                "Whisky2" => (empty($request->input("whisky2")) ? 0:$request->input("whisky2")),
                "Suite" => (empty($request->input("member_suite")) ? 0:$request->input("member_suite")),
                "Suite2" => (empty($request->input("member_suite2")) ? 0:$request->input("member_suite2")),
                "Suite3" => (empty($request->input("member_suite3")) ? 0:$request->input("member_suite3")),
                "Credit_Amt" => (empty($request->input("member_price")) ? 0:$request->input("member_price")),
                "id" => $id
            );
            $res = $this->memberObj->deductMember($updateMem);
            if($res>0){
                $status="01";
                
            }
        }
        $member = $this->memberObj->getMember($id);
        if(is_null($member)){
            return redirect("/member");
        }


        $data["member_status"] = $this->memberObj->getMemberStatus();
        $data["member_type"] =$this->memberObj->getMemberType();
        $data["util"] = $this->util;
        $data["status"] = $status;
        $data["member"] = $member;
        return view("pages.member-deduct",$data);
    }
    public function delete(Request $request){
        $id = $request->input("del_id");
        $result = $this->memberObj->delMember($id);
        $request->session()->put("save_status","success_del");
        return redirect("/member");
    }
    public function paysuccess(Request $request){
        $data["app_id"] = $request->input("id");
        return view("pages.member-complete",$data);
    }
    public function memberbill(Request $request){
        $cashier = $request->session()->get('userinfo')->User_Fullname;
        $id = $request->input("id");
        $pay_cash = 0;
        $pay_credit=0;
        $pay_unpaid = 0;
        $memApp = $this->memberObj->getMemberApplication($id);
        $pay_cash = $memApp->Cash_Amt;
        $pay_credit = $memApp->Credit_Amt;
        $pay_unpaid = $memApp->Unpaid_Amt;
        $data["id"] = $id;
        $data["pay_cash"] = $pay_cash;
        $data["pay_credit"] = $pay_credit;
        $data["pay_unpaid"] = $pay_unpaid;
        $data["util"] = $this->util;
        $data["app"] = $memApp;
        $data["member"] = $this->memberObj->getMember($memApp->SysMember_ID);
        $data["cashier"] = $cashier;
        return view("print.memberbill",$data);
    }
}
