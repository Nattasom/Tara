<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\RoomModel;
use App\CustomModel\MemberModel;
use App\CustomModel\UtilModel;
use App\CustomModel\ConfigModel;
use App\CustomModel\TransactionModel;
use App\CustomModel\Pagination;
use App\CustomModel\UserModel;

class CheckOut extends Controller
{
    private $transObj;
    private $memberObj;
    private $roomObj;
    private $util;
    private $config;
    private $userObj;
    function __construct(){
        $this->userObj = new UserModel();
        $this->roomObj = new RoomModel();
        $this->transObj = new TransactionModel();
        $this->util = new UtilModel();
        $this->config = new ConfigModel();
        $this->memberObj = new MemberModel();
    }
    function index(Request $request){
        // if(!$request->input()->exists("txn") || !$request->input()->exists("txndate")){
        //     return redirect("/checkoutlist");
        // }
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $status = "";
        $txn = $request->input("txn");
        $date= $request->input("txndate");
        if($request->isMethod('post')){
            $action = $request->input("action");
            if($action=="save"){
                $pretty_save = $request->input("pretty_save"); //pretty
                $food = $request->input("food");
                $other = $request->input("other_service");
                $other_remark = $request->input("other_remark");
                $sum_pretty = $request->input("sum_pretty");
                $sum_room= $request->input("sum_room");
                $resHeadUpdate = $this->transObj->savePayment($txn,$date,$food,$other,$other_remark,$sum_pretty,$sum_room,"","","","","",'N',$request->input("member_id"));
                if($resHeadUpdate > 0){//update pretty round
                    $arrPr = explode(',',$pretty_save);
                    foreach($arrPr as $key => $value){
                        $arrRound = explode('=',$value);
                        if(count($arrRound)>1){
                            if(!empty($arrRound[0])&&!empty($arrRound[1])){
                                $this->transObj->updatePrettyPayment($arrRound[0],$arrRound[1],$arrRound[2],"N",$arrRound[3],$arrRound[4],$arrRound[5]);
                            }
                        }
                        
                    }
                }
            }
            else if($action=="payment"){
                $pretty_save = $request->input("pretty_save"); //pretty
                $room_save = $request->input("room_save");
                $food = $request->input("food");
                $other = $request->input("other_service");
                $other_remark = $request->input("other_remark");
                $sum_pretty = $request->input("sum_pretty");
                $sum_room= $request->input("sum_room");
                $food_cash = $request->input("food_cash");
                $food_credit = $request->input("food_credit");
                $pr_cash=$request->input("mas_cash");
                $pr_credit=$request->input("mas_credit");
                 $food_member_amt = 0;
                $mas_member_amt = 0;
                
                if(!empty($request->input("food_member"))){
                    $food_member_amt = intval($request->input("food_member"));
                }
                if(!empty($request->input("mas_member"))){
                    $mas_member_amt = intval($request->input("mas_member"));
                }
                $resHeadUpdate = $this->transObj->savePayment($txn,$date,$food,$other,$other_remark,$sum_pretty,$sum_room,$food_cash,$food_credit,$pr_cash,$pr_credit,($food_member_amt+$mas_member_amt),'Y',$request->input("member_id")); 
                if($resHeadUpdate > 0){
                    $arrPr = explode(',',$pretty_save);
                    foreach($arrPr as $key => $value){ //เหลือ discount
                        $arrRound = explode('=',$value);
                        if(count($arrRound)>1){
                            if(!empty($arrRound[0])&&!empty($arrRound[1])){
                                $this->transObj->updatePrettyPayment($arrRound[0],$arrRound[1],$arrRound[2],"Y",$arrRound[3],$arrRound[4],$arrRound[5]);
                            }
                        }
                        
                    }
                    $this->transObj->updateRoomPaidStatus($txn,$date,"Y");
                    //room
                    $arrRoom = explode(',',$room_save);
                    foreach ($arrRoom as $key => $value) { //เหลือ discount
                        $arrItem = explode('=',$value);
                        if(count($arrItem)>1){
                            if(!empty($arrItem[0])&&!empty($arrItem[1])){
                                $this->transObj->updateRoomPayment($arrItem[0],$arrItem[1],$arrItem[2],$arrItem[3],$arrItem[4]);
                            }
                        }
                    }

                    //keep credit data
                    $fd_credit = 0;
                    $ms_credit = 0;
                    if(!empty($food_credit)){
                        $fd_credit = intval($food_credit);
                    }
                    if(!empty($pr_credit)){    
                        $ms_credit = intval($pr_credit);
                    }
                    if($fd_credit>0||$ms_credit>0){
                        $sum = 0;
                        if($request->input("food_credit_no")==$request->input("mas_credit_no")){ //insert one
                            $sum = $fd_credit + $ms_credit;
                            $params = array(
                                "Txn_Date"=>$date,
                                "Txn_No"=>$txn,
                                "Credit_Card_No"=>$request->input("food_credit_no"),
                                "Credit_Card_type"=>$request->input("food_credit_type"),
                                "Credit_Card_Amt"=>$sum,
                                "Credit_Card_Fnb_Amt"=>$fd_credit,
                                "Credit_Card_Angel_Amt"=>$ms_credit,
                                "Payment_Date"=>date("Y-m-d")
                            );
                            $this->transObj->addCreditPayment($params);
                        }
                        else{
                            if($fd_credit>0){
                                $params = array(
                                    "Txn_Date"=>$date,
                                    "Txn_No"=>$txn,
                                    "Credit_Card_No"=>$request->input("food_credit_no"),
                                    "Credit_Card_type"=>$request->input("food_credit_type"),
                                    "Credit_Card_Amt"=>$fd_credit,
                                    "Credit_Card_Fnb_Amt"=>$fd_credit,
                                    "Payment_Date"=>date("Y-m-d")
                                );
                                $this->transObj->addCreditPayment($params);
                            }
                            if($ms_credit>0){
                                $params = array(
                                    "Txn_Date"=>$date,
                                    "Txn_No"=>$txn,
                                    "Credit_Card_No"=>$request->input("mas_credit_no"),
                                    "Credit_Card_type"=>$request->input("mas_credit_type"),
                                    "Credit_Card_Amt"=>$ms_credit,
                                    "Credit_Card_Angel_Amt"=>$ms_credit,
                                    "Payment_Date"=>date("Y-m-d")
                                );
                                $this->transObj->addCreditPayment($params);
                            }
                        }
                    }
                    //keep unpaid data
                    $unpaid_food_amt = 0;
                    $unpaid_mas_amt = 0;
                    if(!empty($request->input("food_debt"))){
                        $unpaid_food_amt = intval($request->input("food_debt"));
                    }

                    if(!empty($request->input("mas_debt"))){
                        $unpaid_mas_amt = intval($request->input("mas_debt"));
                    }
                    if(!empty($request->input("mas_debt")) || !empty($request->input("food_debt"))){
                        $params = array(
                            "Txn_Date"=>$date,
                            "Txn_No"=>$txn,
                            "Total_Unpaid"=>($unpaid_mas_amt+$unpaid_food_amt),
                            "Total_Remain"=>($unpaid_mas_amt+$unpaid_food_amt),
                            "Paid_Flag"=>'N',
                            "Remark"=>$request->input("mas_debt_remark"),
                            "SysReception_ID"=>$request->input("recept_id"),
                            "Total_Unpaid_Massage"=>$unpaid_mas_amt,
                            "Total_Unpaid_Fnb"=>$unpaid_food_amt,
                            "CreateBy_USER_ID"=>$uid,
                            "Create_Dtm"=>date('Y-m-d H:i:s'),
                            "LastUpd_User_ID"=>$uid,
                            "LastUpd_Dtm"=>date('Y-m-d H:i:s')
                        );
                        $this->transObj->addUnpaidPayment($params);
                    }
                    //keep member_payment
                    $suite_used = 0;
                    if(!empty($request->input("suite_minus"))){
                        $suite_used  = floatval($request->input("suite_minus"));
                        $this->memberObj->cutMemberSuite($request->input("member_id"),$suite_used);
                    }
                    if(!empty($request->input("suite_minus2"))){
                        $suite_used  = floatval($request->input("suite_minus2"));
                        $this->memberObj->cutMemberSuite2($request->input("member_id"),$suite_used);
                    }
                    if(!empty($request->input("food_member"))||!empty($request->input("mas_member")) || $suite_used>0){
                       
                        $params = array(
                            "SysMember_ID"=>$request->input("member_id"),
                            "Txn_Date"=>$date,
                            "Txn_No"=>$txn,
                            "FNB_Paid_Amt"=>$food_member_amt,
                            "Angel_Paid_Amt"=>$mas_member_amt,
                            "Payment_Date"=>date('Y-m-d'),
                            "Member_Suite_Used"=>$suite_used,
                            "LastUpd_dtm"=>date('Y-m-d H:i:s')
                        );
                        $this->transObj->addMemberPayment($params);

                        $this->memberObj->cutCreditAmount($request->input("member_id"),($food_member_amt+$mas_member_amt));
                    }

                    return redirect("/payment/complete?txn=".$txn."&txndate=".$date);
                }
                else{
                    return redirect("/checkoutlist");
                }
            }
        }


        
        $strDate = "";
        $docNo="";
        $strRecept = "";
        $recept_id = "";
        $prFood = "";
        $otherCharge = "";
        $otherChargeRemark = "";
        $paidFlag = "N";
        $head_data = $this->transObj->getHeaderTransaction($txn,$date);
        if(!is_null($head_data)){
            $strDate = $this->util->dateToThaiFormat($head_data->Txn_Date);
            $docNo = str_replace('/','',$strDate).str_pad($head_data->Txn_No,5,'0',STR_PAD_LEFT);
            $strRecept = $head_data->Reception_ID.": ".$head_data->Recept_Nick;
            $recept_id = $head_data->Reception_ID;
            $prFood = $head_data->FNB_Total_Amt;
            $paidFlag = $head_data->Paid_Flag;
            $otherCharge = $head_data->Other_Charge_Amt;
            $otherChargeRemark = $head_data->Other_Charge_Remark;
        }
        $pretty_checkout = true;
        $room_checkout = true;
        $pr_list = $this->transObj->getPrettyListByTxn($txn,$date);
        foreach($pr_list as $key=>$value){
            if(is_null($value->Real_Check_Out)){
                $pretty_checkout = false;
                break;
            }
        }
        $r_list = $this->transObj->getPaymentRoomListByTxn($txn,$date);
        foreach($r_list as $key=>$value){
            if(substr( $value->Room_Type_Code, 0, 2 ) === "SU"){ //not sub suite
                if(is_null($value->Check_Out_Time)){
                    $room_checkout = false;
                    break;
                }
            }
        }

        $data["docNo"] = $docNo;
        $data["strRecept"] = $strRecept;
        $data["strDate"] = $strDate;
        $data["recept_id"]=$recept_id;
        $data["prFood"] = $prFood;
        $data["paidFlag"] = $paidFlag;
        $data["other_charge"] = $otherCharge;
        $data["other_charge_remark"] = $otherChargeRemark;
        $data["util"] = $this->util;
        $data["credit_type"] = $this->config->getCreditCartType();
        $data["prettylist"] = $pr_list;
        $data["roomlist"] = $r_list;
        $data["payment_flag"] = ($pretty_checkout && $room_checkout);

        return view("pages.payment",$data);
    }
    function paymentDetail(Request $request){
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $status = "";
        $txn = $request->input("txn");
        $date= $request->input("txndate");
        $strDate = "";
        $docNo="";
        $strRecept = "";
        $recept_id = "";
        $prFood = "";
        $otherCharge = "";
        $otherChargeRemark = "";
        $paidFlag = "N";
        $member_code = "";
        $member_name="";
        $food_cash = 0;
        $food_credit = 0;
        $food_member = 0;
        $food_debt = 0;
        $mas_cash = 0;
        $mas_credit = 0;
        $mas_member = 0;
        $mas_debt = 0;
        $head_data = $this->transObj->getHeaderTransaction($txn,$date);
        if(!is_null($head_data)){
            $strDate = $this->util->dateToThaiFormat($head_data->Txn_Date);
            $docNo = str_replace('/','',$strDate).str_pad($head_data->Txn_No,5,'0',STR_PAD_LEFT);
            $strRecept = $head_data->Reception_ID.": ".$head_data->Recept_Nick;
            $recept_id = $head_data->Reception_ID;
            $prFood = $head_data->FNB_Total_Amt;
            $paidFlag = $head_data->Paid_Flag;
            $otherCharge = $head_data->Other_Charge_Amt;
            $otherChargeRemark = $head_data->Other_Charge_Remark;
            $member_code = $head_data->Member_ID;
            $member_name = $head_data->Member_Name;
            if(!empty($head_data->FNB_Cash_Amt)){
                $food_cash = $head_data->FNB_Cash_Amt;
            }
            if(!empty($head_data->FNB_Credit_Amt)){
                $food_credit = $head_data->FNB_Credit_Amt;
            }
            $food_member = $head_data->FNB_Member;
            $food_debt = $head_data->FNB_Unpaid;

            if(!empty($head_data->Angel_Cash_Amt)){
                $mas_cash = $head_data->Angel_Cash_Amt;
            }
            if(!empty($head_data->Angel_Credit_Amt)){
                $mas_credit = $head_data->Angel_Credit_Amt;
            }
            $mas_member = $head_data->Mas_Member;
            $mas_debt = $head_data->Mas_Unpaid;
        }
        $data["txn"] = $txn;
        $data["txndate"] = $date;
        $data["member_code"] = $member_code;
        $data["member_name"] = $member_name;
        $data["docNo"] = $docNo;
        $data["strRecept"] = $strRecept;
        $data["strDate"] = $strDate;
        $data["recept_id"]=$recept_id;
        $data["prFood"] = $prFood;
        $data["paidFlag"] = $paidFlag;
        $data["other_charge"] = $otherCharge;
        $data["other_charge_remark"] = $otherChargeRemark;
        $data["util"] = $this->util;
        $data["credit_type"] = $this->config->getCreditCartType();
        $data["prettylist"] = $this->transObj->getPrettyListByTxn($txn,$date);
        $data["roomlist"] = $this->transObj->getPaymentRoomListByTxn($txn,$date);

        $data["food_cash"] = $food_cash;
        $data["food_credit"] = $food_credit;
        $data["food_member"] = $food_member;
        $data["food_debt"] = $food_debt;
        $data["mas_cash"] = $mas_cash;
        $data["mas_credit"] = $mas_credit;
        $data["mas_member"] = $mas_member;
        $data["mas_debt"] = $mas_debt;
        return view("pages.paymentdetail",$data);
    }
    function debtDetail(Request $request){
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $txn = $request->input("txn");
        $date= $request->input("txndate");
        if($request->isMethod('post')){
            $pay_cash = 0;
            $pay_credit = 0;
            $pay_member = 0;
            if(!empty($request->input("mas_cash"))){
                $pay_cash = intval($request->input("mas_cash"));
            }
            if(!empty($request->input("mas_credit"))){
                $pay_credit = intval($request->input("mas_credit"));
            }
            if(!empty($request->input("mas_member"))){
                $pay_member = intval($request->input("mas_member"));
            }
            $res = $this->transObj->payUnpaid($txn,$date,$pay_cash,$pay_credit,$pay_member);
            if($res > 0){
                if($pay_member > 0){
                    $this->memberObj->cutCreditAmount($request->input("member_id"),$pay_member);
                }
                $request->session()->put("save_status","success");
                return redirect("/paymentdebtlist");
            }
        }
        $strDate = "";
        $docNo="";
        $strRecept = "";
        $recept_id = "";
        $prFood = "";
        $otherCharge = "";
        $otherChargeRemark = "";
        $paidFlag = "N";
        $member_pay = 0;
        $food_cash = 0;
        $food_credit = 0;
        $food_member = 0;
        $food_debt = 0;
        $mas_cash = 0;
        $mas_credit = 0;
        $mas_member = 0;
        $mas_debt = 0;
        $head_data = $this->transObj->getHeaderTransaction($txn,$date);
        if(!is_null($head_data)){
            $strDate = $this->util->dateToThaiFormat($head_data->Txn_Date);
            $docNo = str_replace('/','',$strDate).str_pad($head_data->Txn_No,5,'0',STR_PAD_LEFT);
            $strRecept = $head_data->Reception_ID.": ".$head_data->Recept_Nick;
            $recept_id = $head_data->Reception_ID;
            $prFood = $head_data->FNB_Total_Amt;
            $paidFlag = $head_data->Paid_Flag;
            $otherCharge = $head_data->Other_Charge_Amt;
            $otherChargeRemark = $head_data->Other_Charge_Remark;
            if(!empty($head_data->FNB_Cash_Amt)){
                $food_cash = $head_data->FNB_Cash_Amt;
            }
            if(!empty($head_data->FNB_Credit_Amt)){
                $food_credit = $head_data->FNB_Credit_Amt;
            }
            $food_member = $head_data->FNB_Member;
            $food_debt = $head_data->FNB_Unpaid;

            if(!empty($head_data->Angel_Cash_Amt)){
                $mas_cash = $head_data->Angel_Cash_Amt;
            }
            if(!empty($head_data->Angel_Credit_Amt)){
                $mas_credit = $head_data->Angel_Credit_Amt;
            }
            $mas_member = $head_data->Mas_Member;
            $mas_debt = $head_data->Mas_Unpaid;
        }
        $pr_list = $this->transObj->getPrettyListByTxn($txn,$date);
        $sum_pr = 0;
        foreach($pr_list as $key=>$value){
            $sum_pr += ($value->Angel_Fee * $value->Round)-$value->Angel_Discount_Amt;
        }
        $room_list = $this->transObj->getPaymentRoomListByTxn($txn,$date);
        $sum_room = 0;
        foreach($room_list as $key=>$value){
            $sum_room += ($value->Fee-$value->Discount-$value->Suite_Amt);
        }

        $data["docNo"] = $docNo;
        $data["strRecept"] = $strRecept;
        $data["strDate"] = $strDate;
        $data["recept_id"]=$recept_id;
        $data["prFood"] = $prFood;
        $data["paidFlag"] = $paidFlag;
        $data["other_charge"] = $otherCharge;
        $data["other_charge_remark"] = $otherChargeRemark;
        $data["util"] = $this->util;
        $data["credit_type"] = $this->config->getCreditCartType();
        $data["pr_sum"] = $sum_pr;
        $data["room_sum"] = $sum_room;
        $data["pay"] = $food_cash+$food_credit+$food_member+$mas_cash+$mas_credit+$mas_member;
        $data["debt"] = $food_debt+$mas_debt;
        return view("pages.debtdetail",$data);
    }
    //
    function paymentList(){
        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $txndate = $this->transObj->getDateTxn();
        $numrows = $this->transObj->getPaymentListCount($txndate);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->transObj->getPaymentList($txndate,$start,$perpage);

        $status = "";
        // if ($request->session()->exists('save_status')) {
        //     $status = $request->session()->get("save_status");
        //     $request->session()->forget('save_status');
        // }
        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["list"] = $list;
        $data["paging"] = $paging;
        $data["date"] = $this->util->dateToThaiFormat($txndate);
        return view("pages.paymentlist",$data);
    }
    function ajaxPaymentList(Request $request){
        $txndate = $this->util->dateThaiToSystemFormat($request->input("datetrans"));
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->transObj->getPaymentListCount($txndate);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->transObj->getPaymentList($txndate,$start,$perpage);

        $status = "";
        // if ($request->session()->exists('save_status')) {
        //     $status = $request->session()->get("save_status");
        //     $request->session()->forget('save_status');
        // }
        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["list"] = $list;
        $data["paging"] = $paging;
        return view("ajax.paymentlist",$data);
    }
    function paymentDebtList(Request $request){
        $page_id = 2;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->transObj->getPaymentDebtListCount("","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->transObj->getPaymentDebtList("","",$start,$perpage);

       $status = "";
        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["list"] = $list;
        $data["paging"] = $paging;
        $data["flag_edit"] = $flagEdit;
        return view("pages.paymentdebtlist",$data);
    }
    function ajaxPaymentDebtList(Request $request){
        $page_id = 2;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");
        $txndate = "";
        if(!empty($request->input("datetrans"))){
            $txndate = $this->util->dateThaiToSystemFormat($request->input("datetrans"));
        }
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->transObj->getPaymentDebtListCount($request->input("recept_id"),$txndate);
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $list = $this->transObj->getPaymentDebtList($request->input("recept_id"),$txndate,$start,$perpage);

        $status = "";
        $data["status"] = $status;
        $data["util"] = $this->util;
        $data["list"] = $list;
        $data["paging"] = $paging;
        $data["flag_edit"] = $flagEdit;
        return view("ajax.paymentdebtlist",$data);
    }
    function checkoutlist(Request $request){
        $page_id = 19;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagB1 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_2");
        $data["buildinglist"] = $this->roomObj->getBuildingList();
        $data["pretty_list"] = $this->transObj->getPrettyWorkingList($flagB1,$flagB2);
        $data["pretty_checkout_list"] = $this->transObj->getPrettyCheckoutList($flagB1,$flagB2);
        $data["room_list"] = $this->transObj->getSuiteWorkingList($flagB1,$flagB2);
        $data["room_checkout_list"] = $this->transObj->getSuiteCheckoutList($flagB1,$flagB2);
        $data["util"]=$this->util;
        $data["flag_B1"] = $flagB1;
        $data["flag_B2"] = $flagB2;
        return view("pages.checkoutlist",$data);
    }
    function ajaxLoadWorkingList(Request $request){
        $page_id = 19;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagB1 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_2");
        $data["pretty_list"] = $this->transObj->getPrettyWorkingList($flagB1,$flagB2);
        $data["room_list"] = $this->transObj->getSuiteWorkingList($flagB1,$flagB2);
        $data["util"]=$this->util;
        $data["flag_B1"] = $flagB1;
        $data["flag_B2"] = $flagB2;
        return view("ajax.loadworkinglist",$data);
    }
    function ajaxLoadPaymentList(Request $request){
        $page_id = 19;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagB1 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_1");
        $flagB2 = $this->userObj->checkActionUser($uid,$page_id,"EDIT_2");
        $data["pretty_checkout_list"] = $this->transObj->getPrettyCheckoutList($flagB1,$flagB2);
        $data["room_checkout_list"] = $this->transObj->getSuiteCheckoutList($flagB1,$flagB2);
        $data["util"]=$this->util;
        $data["flag_B1"] = $flagB1;
        $data["flag_B2"] = $flagB2;
        return view("ajax.loadpaymentlist",$data);
    }
     function cancelBill(Request $request){
        $response = array();
        $response["status"] = "00";
        if(!is_null($request->input("txn")) && !is_null($request->input("date"))){
            $res = $this->transObj->cancelBill($request->input("txn"),$request->input("date"));
            if($res > 0){
                $response["status"] = "01";
            }
        }

        return response()->json($response);
    }
    function prettyCheckout(Request $request){
        $response = array();
        $response["status"] = "00";
        if(!is_null($request->input("id"))){
            $res = $this->transObj->checkoutPretty($request->input("id"));
            if($res > 0){
                $response["status"] = "01";
            }
        }

        return response()->json($response);
    }
    function prettyAddTime(Request $request){
        $response = array();
        $response["status"] = "00";
        if(!is_null($request->input("update_id"))){
            $res = $this->transObj->addTimePretty($request->input("update_id"),$request->input("round_up"));
            if($res > 0){
                $response["status"] = "01";
            }
        }

        return response()->json($response);
    }
    function moveRoom(Request $request){
        $response = array();
        $response["status"] = "00";
        $txn = $request->input("txn");
        $date = $request->input("date");
        $to_room = $request->input("to_room");
        $roomCheckin = $this->transObj->getRoomCheckin($txn,$date);
        $prettyCheckin = $this->transObj->getPrettyCheckin($txn,$date);
        $is_suite = false;
        foreach($roomCheckin as $key=>$value){
            if(substr( $value->Room_Type_Code, 0, 2 ) === "SU"){
                $is_suite = true;
                break;
            }
        }
        $chkSuite = $this->roomObj->isSuiteRoom($to_room);
        $allPretty = count($prettyCheckin);
        if($is_suite){
            if(!$chkSuite){
                $response["status"] = "02";
                return response()->json($response);
            }
            // $checkin_time = $roomCheckin[0]->Check_In_Time;
            // $to_time = strtotime($checkin_time);
            // $now = time();
            // $diff = round(abs($to_time - $now) / 60,2);
            // if($diff > 15){
            //     $response["status"] = "03";
            //     return response()->json($response);
            // }
            $ss=0;
            $ssList = $this->roomObj->getSubSuiteRoom($to_room);
            $suList = array();
            $subFromCount = 0;
            $subRoomStr = "";
            foreach($roomCheckin as $key=>$value){ // update room
                $recept_id="";
                $warn_flag = "";
                $recept_id = $value->SysReception_ID;
                $warn_flag = $value->Warning_Flag;
                 if(substr( $value->Room_Type_Code, 0, 2 ) === "SU"){
                    $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
                    $this->transObj->moveRoom($value->SysMassage_Room_List_ID,$to_room,"R");
                    $suList = $this->roomObj->getSubSuiteRoom($value->SysRoom_ID);
                  }else{
                    if(array_key_exists($ss,$ssList)){
                        $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
                        $this->transObj->moveRoom($value->SysMassage_Room_List_ID,$ssList[$ss]->SysRoom_ID,"R");
                        if($ss !=0){
                            $subRoomStr .=",";
                        }
                        $subRoomStr .= "".$ssList[$ss]->SysRoom_ID;
                        $ss++;
                    }else{
                        $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
                        $this->transObj->delTransRoom($value->SysMassage_Room_List_ID);
                    }
                    
                    // else{
                    //     $ss=0;
                    //     $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
                    //     $this->transObj->moveRoom($value->SysMassage_Room_List_ID,$ssList[$ss]->SysRoom_ID,"R");
                    //     $ss++;
                    // }
                   
                  }
                  
            }
            //clear old room sub
            foreach($suList as $key=>$value){
                $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
            }
            //to room more than from room (insert tmp)
            // $stat = "";
            if(count($ssList) > $ss){
                $arrSubroom = explode(',',$subRoomStr);
                //$stat .=" loop>";
                foreach($ssList as $k=>$v){
                    if(!in_array($v->SysRoom_ID, $arrSubroom)){
                        //$stat .=$v->SysRoom_ID."-";
                        $this->transObj->roomCheckIn($txn,$date,$v->SysRoom_ID,$recept_id,$warn_flag);
                    }
                }
            }
            $ss=0;
            foreach($prettyCheckin as $key=>$value){ //update pretty

                if(array_key_exists($ss,$ssList)){
                    $this->transObj->moveRoom($value->SysMassage_Angel_List_ID,$ssList[$ss]->SysRoom_ID,"PR");
                }else{
                    $ss=0;
                    $this->transObj->moveRoom($value->SysMassage_Angel_List_ID,$ssList[$ss]->SysRoom_ID,"PR");
                }
                
                $ss++;
            }
            $response["status"] = "01";
        }
        else{
            
            if(!$chkSuite){
                foreach($roomCheckin as $key=>$value){ // update room
                    $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
                    $this->transObj->moveRoom($value->SysMassage_Room_List_ID,$to_room,"R");
                }
                foreach($prettyCheckin as $key=>$value){ //update pretty
                    $this->transObj->moveRoom($value->SysMassage_Angel_List_ID,$to_room,"PR");
                }
            }else{
                $recept_id="";
                $warn_flag = "";
                $ssList = $this->roomObj->getSubSuiteRoom($to_room);
                foreach($roomCheckin as $key=>$value){ // update room
                    $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
                    $this->transObj->moveToSuiteRoom($value->SysMassage_Room_List_ID,$to_room);
                    $recept_id = $value->SysReception_ID;
                    $warn_flag = $value->Warning_Flag;
                }
                foreach($ssList as $key=>$value){
                    $this->transObj->roomCheckIn($txn,$date,$value->SysRoom_ID,$recept_id,$warn_flag);
                }

                $i=0;
                foreach($prettyCheckin as $key=>$value){ //update pretty
                    if(array_key_exists($i,$ssList)){
                        $this->transObj->moveRoom($value->SysMassage_Angel_List_ID,$ssList[$i]->SysRoom_ID,"PR");
                    }else{
                        $i=0;
                        $this->transObj->moveRoom($value->SysMassage_Angel_List_ID,$ssList[$i]->SysRoom_ID,"PR");
                    }
                    
                    $i++;
                }
            }
            
            $response["status"] = "01";
        }

        return response()->json($response);
    }
    function roomAddTime(Request $request){
        $response = array();
        $response["status"] = "00";
        if(!is_null($request->input("update_id"))){
            $res = $this->transObj->addTimeRoom($request->input("update_id"),$request->input("round_up"));
            if($res > 0){
                $response["status"] = "01";
            }
        }

        return response()->json($response);
    }
    function roomEditTime(Request $request){
        $response = array();
        $response["status"] = "00";
        if(!is_null($request->input("edit_id"))){
            $res = $this->transObj->editTimeRoom($request->input("edit_id"),$request->input("txt_edit_time"));
            if($res > 0){
                $response["status"] = "01";
            }
        }

        return response()->json($response);
    }
    function prettyEditTime(Request $request){
        $response = array();
        $response["status"] = "00";
        if(!is_null($request->input("edit_id"))){
            $res = $this->transObj->editTimePretty($request->input("edit_id"),$request->input("txt_edit_time"));
            if($res > 0){
                $response["status"] = "01";
            }
        }

        return response()->json($response);
    }
    function roomCheckout(Request $request){
        $response = array();
        $response["status"] = "00";
        if(!is_null($request->input("id"))){
            $res = $this->transObj->checkoutRoom($request->input("id"));
            if($res > 0){
                $response["status"] = "01";
            }
        }

        return response()->json($response);
    }
    function updateRecept(Request $request){
        $id = $request->input("id");
        $recept = $request->input("recept");
        $response = array();
        $response["status"] = "00";
        if(!is_null($id)){
            $res = $this->transObj->updateReception($id,$recept);
            if($res > 0){
                $response["status"] = "01";
            }
        }
        return response()->json($response);
    }
    function receiptbill(Request $request){
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $cashier = $request->session()->get('userinfo')->User_Fullname;
        $status = "";
        $txn = $request->input("txn");
        $date= $request->input("txndate");
        $head_data = $this->transObj->getHeaderTransaction($txn,$date);
        if(!is_null($head_data)){
            $strDate = $this->util->dateToThaiFormat($head_data->Txn_Date);
            $docNo = str_replace('/','',$strDate).str_pad($head_data->Txn_No,5,'0',STR_PAD_LEFT);
            $strRecept = $head_data->Reception_ID.": ".$head_data->Recept_Nick;
            $recept_id = $head_data->Reception_ID;
            $prFood = $head_data->FNB_Total_Amt;
            $paidFlag = $head_data->Paid_Flag;
            $otherCharge = $head_data->Other_Charge_Amt;
            $otherChargeRemark = $head_data->Other_Charge_Remark;
        }
        $data["docNo"] = $docNo;
        $data["strRecept"] = $strRecept;
        $data["strDate"] = $strDate;
        $data["recept_id"]=$recept_id;
        $data["prFood"] = $prFood;
        $data["other_charge"] = $otherCharge;
        $data["other_charge_remark"] = $otherChargeRemark;
        $data["util"] = $this->util;
        $data["cashier"] = $cashier;
        $data["prettylist"] = $this->transObj->getPrettyListByTxn($txn,$date);
        $data["roomlist"] = $this->transObj->getPaymentRoomListByTxn($txn,$date);
        //cash
        $pay_cash = 0;
        if(!empty($head_data->FNB_Cash_Amt)){
            $pay_cash += intval($head_data->FNB_Cash_Amt);
        }
        if(!empty($head_data->Angel_Cash_Amt)){
            $pay_cash += intval($head_data->Angel_Cash_Amt);
        }
        //credit
        $pay_credit = 0;
        if(!empty($head_data->FNB_Credit_Amt)){
            $pay_credit += intval($head_data->FNB_Credit_Amt);
        }
        if(!empty($head_data->Angel_Credit_Amt)){
            $pay_credit += intval($head_data->Angel_Credit_Amt);
        }
        //member
        $pay_member = 0;
        if(!empty($head_data->Member_Fee_Amt)){
            $pay_member += intval($head_data->Member_Fee_Amt);
        }
        //unpaid
        $pay_unpaid = $this->transObj->getTotalUnpaidByTxn($txn,$date)->Total;

        $member_data = null;
        if(!empty($head_data->SysMember_ID)){
            $member_data = $this->memberObj->getMember($head_data->SysMember_ID);
        }


        $data["pay_cash"] = $pay_cash;
        $data["pay_credit"] = $pay_credit;
        $data["pay_member"] = $pay_member;
        $data["pay_unpaid"] = $pay_unpaid;
        $data["member_data"] = $member_data;
        return view("print.receiptbill",$data);
    }
    function paymentbill(Request $request){
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $cashier = $request->session()->get('userinfo')->User_Fullname;
        $status = "";
        $txn = $request->input("txn");
        $date= $request->input("txndate");
        $head_data = $this->transObj->getHeaderTransaction($txn,$date);
        if(!is_null($head_data)){
            $strDate = $this->util->dateToThaiFormat($head_data->Txn_Date);
            $docNo = str_replace('/','',$strDate).str_pad($head_data->Txn_No,5,'0',STR_PAD_LEFT);
            $strRecept = $head_data->Reception_ID.": ".$head_data->Recept_Nick;
            $recept_id = $head_data->Reception_ID;
            $prFood = $head_data->FNB_Total_Amt;
            $paidFlag = $head_data->Paid_Flag;
            $otherCharge = $head_data->Other_Charge_Amt;
            $otherChargeRemark = $head_data->Other_Charge_Remark;
        }
        $data["docNo"] = $docNo;
        $data["strRecept"] = $strRecept;
        $data["strDate"] = $strDate;
        $data["recept_id"]=$recept_id;
        $data["prFood"] = $prFood;
        $data["other_charge"] = $otherCharge;
        $data["util"] = $this->util;
        $data["cashier"] = $cashier;
        $data["prettylist"] = $this->transObj->getPrettyListByTxn($txn,$date);
        $data["roomlist"] = $this->transObj->getPaymentRoomListByTxn($txn,$date);
        //cash
        $pay_cash = 0;
        if(!empty($head_data->FNB_Cash_Amt)){
            $pay_cash += intval($head_data->FNB_Cash_Amt);
        }
        if(!empty($head_data->Angel_Cash_Amt)){
            $pay_cash += intval($head_data->Angel_Cash_Amt);
        }
        //credit
        $pay_credit = 0;
        if(!empty($head_data->FNB_Credit_Amt)){
            $pay_credit += intval($head_data->FNB_Credit_Amt);
        }
        if(!empty($head_data->Angel_Credit_Amt)){
            $pay_credit += intval($head_data->Angel_Credit_Amt);
        }
        //member
        $pay_member = 0;
        if(!empty($head_data->Member_Fee_Amt)){
            $pay_member += intval($head_data->Member_Fee_Amt);
        }
        //unpaid
        $pay_unpaid = $this->transObj->getTotalUnpaidByTxn($txn,$date)->Total;

        $member_data = null;
        if(!empty($head_data->SysMember_ID)){
            $member_data = $this->memberObj->getMember($head_data->SysMember_ID);
        }


        $data["pay_cash"] = $pay_cash;
        $data["pay_credit"] = $pay_credit;
        $data["pay_member"] = $pay_member;
        $data["pay_unpaid"] = $pay_unpaid;
        $data["member_data"] = $member_data;
        return view("print.paymentbill",$data);
    }
    function paymentComplete(Request $request){
        $data["txn"]=$request->input("txn");
        $data["txndate"]=$request->input("txndate");
        return view("pages.payment-complete",$data);
    }
    function telSave(Request $request){
        $id  = $request->input("id");
        $this->transObj->telSave($id);
        return response()->json([]);
    }
    function telRoomSave(Request $request){
        $id  = $request->input("id");
        $this->transObj->telRoomSave($id);
        return response()->json([]);
    }
}
