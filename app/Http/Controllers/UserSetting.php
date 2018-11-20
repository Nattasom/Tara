<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\UserModel;
use App\CustomModel\Pagination;

class UserSetting extends Controller
{
    private $userObj;
    function __construct(){
        $this->userObj = new UserModel();
    }
    //user setting
    function index(Request $request){
        $page_id = 11;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->userObj->getCount("","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $userlist = $this->userObj->getUserList("","",$start,$perpage);
        $status = "";
        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        return view('pages.usersetting',["userlist"=>$userlist,
        "paging"=>$paging,"status"=>$status,"flag_edit"=>$flagEdit]);
    }
    function useradd(Request $request){
        $status = "";
        $message = "";
        $data = array();
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $loginname = "";
        $fullname = "";
        $citizen = "";
        $address = "";
        $tel = "";
        $rfid = "";
        $datestart_post = "";
        $dateend_post = "";
        $rolesave=array();
        if($request->isMethod('post')){
            // $arrStart = explode('/',$request->input("datestart"));
            // $arrEnd = explode('/',$request->input("dateend"));
            // $datestart= (intval($arrStart[2])-543)."-".$arrStart[1]."-".$arrStart[0]." 00:00:00";
            // $dateend = (intval($arrEnd[2])-543)."-".$arrEnd[1]."-".$arrEnd[0]." 23:59:59";
            $loginname = $request->input("loginname");
            $fullname = $request->input("fullname");
            $citizen = $request->input("citizen");
            $address = $request->input("address");
            $tel = $request->input("phone");
            $rfid = $request->input("rfid");
            $datestart_post = $request->input("datestart");
            $dateend_post = $request->input("dateend");
            $params = array(
                "Login_Name"=>$request->input("loginname"),
                "Password"=>md5($request->input("password")),
                "User_Fullname"=>$request->input("fullname"),
                "User_Citizen_ID"=>$request->input("citizen"),
                "User_Address"=>$request->input("address"),
                "User_Tel_No"=>$request->input("phone"),
                "RF_Card_No"=>$request->input("rfid"),
                // "Valid_From_Date"=>$datestart,
                // "Valid_To_Date"=>$dateend,
                "User_Status"=>'AC',
                "LastUpd_User_Dtm"=>date("Y-m-d h:i:s"),
                "LastUpd_User_ID"=>$uid,
                "Delete_Flag"=>'N'
            );
            $rolesave = explode(',',$request->input("role_save"));
            $result = $this->userObj->addUser($params,$rolesave);
            if($result>0){
                $request->session()->put("save_status","success");
                return redirect("/usersetting");
            }
            else if($result==0){//duplicate user
                $status="00";
                $message="ชื่อผู้ใช้งานซ้ำ";
            }
        }
        $rolelist = $this->userObj->getRoleList("","","",0,100);
        $data["rolelist"] = $rolelist;
        $data["status"]=$status;
        $data["message"] = $message;
        $data["loginname"]=$loginname;
        $data["fullname"]=$fullname;
        $data["citizen"]=$citizen;
        $data["address"]=$address;
        $data["tel"]=$tel;
        $data["rfid"]=$rfid;
        $data["datestart"]=$datestart_post;
        $data["dateend"]=$dateend_post;
        $data["rolesave"]=$rolesave;
        return view('pages.user-add',$data);
    }
     function useredit(Request $request,$id){
         if(is_null($id)){
            return redirect("/usersetting");
        }
        $uid = $request->session()->get('userinfo')->SysUser_ID;
         $status = "";
         $message = "";
         if($request->isMethod('post')){
            $loginname = $request->input("loginname");
            $fullname = $request->input("fullname");
            $citizen = $request->input("citizen");
            $address = $request->input("address");
            $tel = $request->input("phone");
            $rfid = $request->input("rfid");
            $params = array(
                "Login_Name"=>$request->input("loginname"),
                "User_Fullname"=>$request->input("fullname"),
                "User_Citizen_ID"=>$request->input("citizen"),
                "User_Address"=>$request->input("address"),
                "User_Tel_No"=>$request->input("phone"),
                "RF_Card_No"=>$request->input("rfid"),
                "LastUpd_User_Dtm"=>date("Y-m-d h:i:s"),
                "LastUpd_User_ID"=>$uid,
                "id"=>$id
            );
            $rolesave = explode(',',$request->input("role_save"));
            $result = $this->userObj->editUser($params,$rolesave);
            if($result > 0){
                if(!is_null($request->input("changepass"))){
                    $this->userObj->editPasswordUser(md5($request->input("password")),$params["id"]);
                }
                $status = "01";
                $message = "บันทึกข้อมูลเรียบร้อย";
            }
         }
         $rolelist = $this->userObj->getRoleList("","","",0,100);
         $user = $this->userObj->getUser($id);
         $userrole = $this->userObj->getUserRoleArray($id);

         //data to view
        $data["rolelist"]=$rolelist;
        $data["status"]=$status;
        $data["message"]=$message;
        $data["user"] = $user;
        $data["userrole"]=$userrole;
        return view('pages.user-edit',$data);
    }
    function changePassword(Request $request){
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $status="";
        $message = "";
        if($request->isMethod('post')){
            $res = $this->userObj->editPasswordUser(md5($request->input("password")),$uid);
            if($res > 0){
                 $status = "01";
                    $message = "บันทึกข้อมูลเรียบร้อย";
            }
            else{
                 $status = "0";
                $message = "ไม่สามารถเปลี่ยนรหัสผ่านได้";
            }
        }

        $data["status"] = $status;
        $data["message"] = $message;
        return view("pages.change-password",$data);
    }
    function ajaxUser(Request $request){
        $page_id = 11;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");
        
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $limit_pg = 5;
        $numrows = $this->userObj->getCount($request->input("loginname"),$request->input("fullname"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $userlist = $this->userObj->getUserList($request->input("loginname"),$request->input("fullname"),$start,$perpage);

        return view('ajax.loaduser',["userlist"=>$userlist,
        "paging"=>$paging,"flag_edit"=>$flagEdit]);
    }

    //user role
    function userrole(Request $request){
        $page_id = 10;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $limit_pg = 5;
        $numrows = $this->userObj->getRoleCount("","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $rolelist = $this->userObj->getRoleList("","","");
        $status = "";
        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        return view('pages.userrole',["rolelist"=>$rolelist,"paging"=>$paging,"status"=>$status,"flag_edit"=>$flagEdit]);
    }
    function ajaxUserRole(Request $request){
        $page_id = 10;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->userObj->getRoleCount($request->input("rolecode"),$request->input("rolename"),$request->input("roledesc"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $rolelist = $this->userObj->getRoleList($request->input("rolecode"),$request->input("rolename"),$request->input("roledesc"),$start,$perpage);

        return view('ajax.loaduserrole',["rolelist"=>$rolelist,"paging"=>$paging,"flag_edit"=>$flagEdit]);
    }
    function userroleadd(Request $request){
        $status="";
        $message="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "Role_Code"=>$request->input('rolecode'),
                "Role_Name"=>$request->input('rolename'),
                "Role_Desc"=>$request->input('roledesc'),
                "Delete_Flag"=>'N',
                "LastUpd_Dtm"=>date("Y-m-d h:i:s"),
                "LastUpd_User_ID"=>$uid
            );
            $result = $this->userObj->addRole($params);
            if($result>0){
                $request->session()->put("save_status","success");
                return redirect("/userrole");
            }
            else if($result==0){//duplicate 
                $status="00";
                $message="ไม่สามารถเพิ่มข้อมูลได้";
            }
        }
        return view('pages.userrole-add',["status"=>$status,"message"=>$message]);
    }
     function userroleedit(Request $request,$id){
        $status="";
        $message="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "Role_Code"=>$request->input('rolecode'),
                "Role_Name"=>$request->input('rolename'),
                "Role_Desc"=>$request->input('roledesc'),
                "LastUpd_Dtm"=>date("Y-m-d h:i:s"),
                "LastUpd_User_ID"=>$uid,
                "id"=>$id
            );
            $result = $this->userObj->editRole($params);
            if($result > 0){
                $status = "01";
                $message="บันทึกข้อมุลเรียบร้อย";
            }else{
                $status = "00";
                $message = "ไม่สามารถบันทึกข้อมูลได้";
            }
        }
        $role = $this->userObj->getRole($id);
        return view('pages.userrole-edit',["role"=>$role,"status"=>$status,"message"=>$message]);
    }
    function userrolepermission(Request $request,$id){
        $status="";
        $message="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $result = $this->userObj->addRolePermission($request->input("str_save"),$id);
            if($result > 0){
                $status="01";
                $message="บันทึกข้อมูลเรียบร้อย";
            }
            else{
                $status="00";
                $message="ไม่สามารถบันทึกข้อมูลได้";
            }
            // $params = array(
            //     "Role_Code"=>$request->input('rolecode'),
            //     "Role_Name"=>$request->input('rolename'),
            //     "Role_Desc"=>$request->input('roledesc'),
            //     "LastUpd_Dtm"=>date("Y-m-d h:i:s"),
            //     "LastUpd_User_ID"=>$uid,
            //     "id"=>$id
            // );
            // $result = $this->userObj->editRole($params);
            // if($result > 0){
            //     $status = "01";
            //     $message="บันทึกข้อมุลเรียบร้อย";
            // }else{
            //     $status = "00";
            //     $message = "ไม่สามารถบันทึกข้อมูลได้";
            // }
        }
        $role = $this->userObj->getRole($id);
        $data["page_group"] = $this->userObj->getPageGroup();
        $data["page"] = $this->userObj->getPageWithAction();
        $data["userObj"] = $this->userObj;
        $data["role_id"] = $id;
        $data["role"] = $role;
        $data["status"] = $status;
        $data["message"] = $message;
        return view('pages.userrole-permission',$data);
    }
    function userroledel(Request $request){
        $id = $request->input('del_id');
        $result = $this->userObj->delRole($id);
        $request->session()->put("save_status","success_del");
        return redirect("/userrole");
    }
    function userdel(Request $request){
        $id = $request->input('del_id');
        $result = $this->userObj->delUser($id);
        $request->session()->put("save_status","success_del");
        return redirect("/usersetting");
    }
    
}
