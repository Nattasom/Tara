<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomModel\RoomModel;
use App\CustomModel\Pagination;
use App\CustomModel\UserModel;

class RoomSetting extends Controller
{
     private $roomObj;
     private $userObj;
    function __construct(){
        $this->roomObj = new RoomModel();
        $this->userObj = new UserModel();
    }
    //
    function index(Request $request){
        $page_id = 13;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->roomObj->getCount("","","","");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $roomlist = $this->roomObj->getRoomList("","","","",$start,$perpage);
        $roomtypelist = $this->roomObj->getRoomTypeList();
        $status = "";
        if ($request->session()->exists('save_status')) {
            $status = $request->session()->get("save_status");
            $request->session()->forget('save_status');
        }
        return view("pages.roomsetting",["roomlist"=>$roomlist,
        "paging"=>$paging,"roomtypelist"=>$roomtypelist,"status"=>$status,"flag_edit"=>$flagEdit]);
    }
    function ajaxRoom(Request $request){
        $page_id = 13;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->roomObj->getCount($request->input("roomno"),$request->input("floor"),$request->input("type"),$request->input("status"));
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $roomlist = $this->roomObj->getRoomList($request->input("roomno"),$request->input("floor"),$request->input("type"),$request->input("status"),$start,$perpage);

        return view("ajax.loadroom",["roomlist"=>$roomlist,
        "paging"=>$paging,"flag_edit"=>$flagEdit]);
    }
    function ajaxRoomPopup(Request $request){
        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->roomObj->getCount($request->input("roomno"),"",$request->input("type"),"");
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;
        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);

        $roomlist = $this->roomObj->getRoomList($request->input("roomno"),"",$request->input("type"),"",$start,$perpage);

        return view("ajax.loadroom-popup",["roomlist"=>$roomlist,
        "paging"=>$paging]);
    }
    function roomtype(Request $request){
        $page_id = 14;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = 1;
        $perpage = 10;
        $numpage = 0;
        $numrows = $this->roomObj->getTypeCount();
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $roomtypelist = $this->roomObj->getRoomTypeList($start,$perpage);
        return view("pages.roomtypesetting",["paging"=>$paging,"roomtypelist"=>$roomtypelist,"flag_edit"=>$flagEdit]);
    }
    function ajaxRoomType(Request $request){
        $page_id = 14;
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        $flagEdit = $this->userObj->checkActionUser($uid,$page_id,"EDIT");

        $curpage = $request->input("page");
        $perpage = $request->input("perpage");
        $numpage = 0;
        $numrows = $this->roomObj->getTypeCount();
        $numpage = ceil($numrows / $perpage);
        $start = ($curpage - 1) * $perpage;

        $paging = new Pagination($curpage,$perpage,$numpage,$numrows,$start);
        $roomtypelist = $this->roomObj->getRoomTypeList($start,$perpage);
        return view("ajax.loadroomtype",["paging"=>$paging,"roomtypelist"=>$roomtypelist,"flag_edit"=>$flagEdit]);
    }
    function roomadd(Request $request){
        $params = array();
        $status="";
        $message="";
        $roomno = "";
        $parentid = "";
        $parentcode="";
        $building = "";
        $floor = "";
        $type= "";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $roomno = $request->input('roomno');
            $parentid = $request->input('parent_id');
            $parentcode=$request->input('parent_code');
            $building = $request->input('building');
            $floor = $request->input('floor');
            $type= $request->input('type');
            $params = array(
                "SysRoom_Type_ID"=>$type,
                "SysParent_Room_ID"=>$parentid,
                "Floor"=>$floor,
                "Room_No"=>$roomno,
                "Room_Status"=>"VA",
                "Delete_Flag"=>"N",
                "building_id"=>$building,
                "LastUpd_Dtm"=>date("Y-m-d h:i:s"),
                "LastUpd_User_ID"=>$uid
            );
            $result = $this->roomObj->addRoom($params);
            if($result>0){
                $request->session()->put("save_status","success");
                return redirect("/roomsetting");
            }
            else if($result==0){//duplicate roomno
                $status="00";
                $message="เลขที่ห้องพักซ้ำ";
            }
        }

        $roomtypelist = $this->roomObj->getRoomTypeList(0,100);
        $buildinglist = $this->roomObj->getBuildingList();
        return view("pages.roomsetting-add",["roomtypelist"=>$roomtypelist,
                "buildinglist"=>$buildinglist,
                "status"=>$status,
                "message"=>$message,
                "type"=>$type,
                "parent_id"=>$parentid,
                "parent_code"=>$parentcode,
                "floor"=>$floor,
                "roomno"=>$roomno,
                "building"=>$building]);
    }
    function roomedit(Request $request,$id){
        if(is_null($id)){
            return redirect("/roomsetting");
        }
        $status="";
        $message="";
        $uid = $request->session()->get('userinfo')->SysUser_ID;
        if($request->isMethod('post')){
            $params = array(
                "SysRoom_Type_ID"=>$request->input('type'),
                "SysParent_Room_ID"=>$request->input('parent_id'),
                "Floor"=>$request->input('floor'),
                "Room_No"=>$request->input('roomno'),
                "Room_Status"=>$request->input('status'),
                "building_id"=>$request->input('building'),
                "LastUpd_Dtm"=>date("Y-m-d h:i:s"),
                "LastUpd_User_ID"=>$uid,
                "id"=>$id
            );
            $result = $this->roomObj->editRoom($params);
            if($result>0){
                $status = "01";
                $message = "บันทึกข้อมูลเรียบร้อย";
            }
            else if($result==0){//duplicate roomno
                $status="00";
                $message="เลขที่ห้องพักซ้ำ";
            }
        }
        $room = $this->roomObj->getRoom($id);
        $roomtypelist = $this->roomObj->getRoomTypeList(0,100);
        $buildinglist = $this->roomObj->getBuildingList();
        return view("pages.roomsetting-edit",["roomtypelist"=>$roomtypelist,"buildinglist"=>$buildinglist,
        "status"=>$status,
        "message"=>$message,
        "room"=>$room
        ]);
    }
    function roomdel(Request $request){
        $id = $request->input("del_id");
        $result = $this->roomObj->delRoom($id);
        $request->session()->put("save_status","success_del");
        return redirect("/roomsetting");
    }
    function roomtypeadd(Request $request){
        if($request->isMethod('post')){ //post
            $uid = $request->session()->get('userinfo')->SysUser_ID;
            $code = $request->input("type_code");
            $name = $request->input("type_name");
            $priceStart = $request->input("type_price_start");
            $price = $request->input("type_price");
            $result = $this->roomObj->addRoomType($code,$name,$priceStart,$price,$uid);
            if($result>0){
                return redirect("/roomtypesetting");
            }
        }
        return view("pages.roomtype-add");
    }
    function roomtypeedit(Request $request,$id){
        if(is_null($id)){
            return redirect("/roomtypesetting");
        }
        $result = array();
        $result["status"]="";
        $result["message"]="";
        if($request->isMethod('post')){ //post
            $uid = $request->session()->get('userinfo')->SysUser_ID;
            $code = $request->input("type_code");
            $name = $request->input("type_name");
            $priceStart = $request->input("type_price_start");
            $price = $request->input("type_price");
            $res = $this->roomObj->editRoomType($code,$name,$priceStart,$price,$uid,$id);
            
            if($res > 0){
                $result["status"] = "01";
                $result["message"] = "บันทึกเรียบร้อย";
            }
            else{
                $result["status"] = "00";
                $result["message"] = "เกิดข้อผิดพลาดบางอย่าง ไม่สามารถบันทึกรายการได้";
            }
        }
        $data = $this->roomObj->getRoomType($id);
        return view("pages.roomtype-edit",["data"=>$data,"result"=>$result]);
    }
    function roomtypedel(Request $request){
        $id = $request->input("del_id");
        $result = $this->roomObj->delRoomType($id);
        return redirect("/roomtypesetting");
    }
}
