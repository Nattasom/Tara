<?php

namespace App\CustomModel;

use Illuminate\Support\Facades\DB;

class RoomModel
{
    public function getRoomList($roomno,$floor,$type,$stat,$start=0,$length=10){
        $where = array();
        $sql = "SELECT a.SysRoom_ID,a.Room_No,a.Floor,b.Room_Type_Desc,(
CASE WHEN a.Room_Status = 'IN' THEN 'ใช้งาน' 
	 WHEN a.Room_Status = 'VA' THEN 'ว่าง'
     WHEN a.Room_Status = 'CL' THEN 'ปิดปรับปรุง'
ELSE '' END 
) as Room_Status_Text ,a.Room_Status
    FROM mas_room a
        INNER JOIN mas_room_type b ON a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.Delete_Flag = 'N'";
       if(!empty($roomno)){
            $sql .=" AND a.Room_No LIKE :roomno";
            $where["roomno"]= "%".$roomno."%";
       }
       if(!empty($floor)){
           $sql .=" AND a.Floor = :floor";
           $where["floor"] = $floor;
       }
       if(!empty($type)){
           $sql .=" AND a.SysRoom_Type_ID = :type";
           $where["type"]=$type;
       }
       if(!empty($stat)){
           $sql .=" AND a.Room_Status = :stat";
           $where["stat"]=$stat;
       }
       $sql .=" Order By a.Room_No,a.Room_Order ";
       $sql .= " LIMIT ".$start.",".$length;
        $userlist = DB::select($sql,$where);
        return $userlist;
    }
    public function getCount($roomno,$floor,$type,$stat){
        $where = array();
        $sql = "SELECT Count(*) as cc
    FROM mas_room a
        INNER JOIN mas_room_type b ON a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.Delete_Flag = 'N'";
       if(!empty($roomno)){
            $sql .=" AND a.Room_No LIKE :roomno";
            $where["roomno"]= "%".$roomno."%";
       }
       if(!empty($floor)){
           $sql .=" AND a.Floor = :floor";
           $where["floor"] = $floor;
       }
       if(!empty($type)){
           $sql .=" AND a.SysRoom_Type_ID = :type";
           $where["type"]=$type;
       }
       if(!empty($stat)){
           $sql .=" AND a.Room_Status = :stat";
           $where["stat"]=$stat;
       }
       $count = collect(\DB::select($sql,$where))->first();
       return $count->cc;
    }
    public function getBuildingList(){
        $where = array();
        $sql = "SELECT * FROM mas_building ";
        $list = DB::select($sql,$where);
        return $list;
    }
    public function getRoomTypeList($start=0,$length=10){
        $where = array();
        $sql = "SELECT * FROM mas_room_type WHERE Delete_Flag = 'N'";
        $sql .= " LIMIT ".$start.",".$length;
        $list = DB::select($sql,$where);
        return $list;
    }
    public function getTypeCount(){
        $where = array();
        $sql = "SELECT Count(*) as cc FROM mas_room_type WHERE Delete_Flag = 'N'";
        $count = collect(\DB::select($sql,$where))->first();
        return $count->cc;
    }
    public function getRoomType($id){
        $where = array();
        $sql = "SELECT * FROM mas_room_type WHERE SysRoom_Type_ID = :id";
        $where["id"] = $id;

        $type = collect(\DB::select($sql,$where))->first();
        return $type;
    }
    public function getRoom($id){
        $where = array();
        $sql = "SELECT a.SysRoom_ID,a.SysRoom_Type_ID,a.SysParent_Room_ID,a.Floor,a.Room_No,a.Room_Status,a.building_id,
        (SELECT Room_No FROM mas_room WHERE SysRoom_ID = a.SysParent_Room_ID) as ParentRoom FROM mas_room a WHERE a.SysRoom_ID = :id";
        $where["id"] = $id;

        $room = collect(\DB::select($sql,$where))->first();
        return $room;
    }
    public function addRoom($params = array()){
        //check duplicate room
        $sql = "select Count(*) as cc FROM mas_room WHERE Room_No=? AND building_id=? AND Delete_Flag='N'";
        $chk = collect(\DB::select($sql,[$params["Room_No"],$params["building_id"]]))->first();
        if($chk->cc > 0){//dup
            return 0;
        }

        $id = DB::table('mas_room')->insertGetId($params);
        return $id;
    }
    public function editRoom($params = array()){
        //check duplicate room
        $sql = "select Count(*) as cc FROM mas_room WHERE Room_No=? AND building_id=? AND SysRoom_ID <> ? AND Delete_Flag='N'";
        $chk = collect(\DB::select($sql,[$params["Room_No"],$params["building_id"],$params["id"]]))->first();
        if($chk->cc > 0){//dup
            return 0;
        }
        $sql = "UPDATE mas_room
                    SET
                        SysRoom_Type_ID = :SysRoom_Type_ID,
                        SysParent_Room_ID = :SysParent_Room_ID,
                        `Floor` = :Floor,
                        Room_No = :Room_No,
                        Room_Status = :Room_Status,
                        building_id = :building_id,
                        LastUpd_Dtm = :LastUpd_Dtm,
                        LastUpd_User_ID = :LastUpd_User_ID
                    WHERE SysRoom_ID = :id
        ";
        $id = DB::update($sql, $params);
        return $id;
    }
    public function delRoom($id){
        $params = array();
        $params["id"] = $id;
        $sql="update mas_room set Delete_Flag = 'Y' Where SysRoom_ID = :id";
        $res = DB::update($sql, $params);
        return $res;
    }
    public function addRoomType($code,$name,$priceStart,$price,$uid){
        $params = array();
        $params["Room_Type_Code"]=$code;
        $params["Room_Type_Desc"]=$name;
        $params["Room_Rate_Start"]=$priceStart;
        $params["Room_Rate"]=$price;
        $params["Delete_Flag"] = 'N';
        $params["LastUpd_Dtm"]= date("Y-m-d h:i:s");
        $params["LastUpd_User_ID"] = $uid;
        $id = DB::table('mas_room_type')->insertGetId($params);
        return $id;
    }
    public function editRoomType($code,$name,$priceStart,$price,$uid,$id){
        $params = array();
        $params["id"] = $id;
        $params["Room_Type_Code"]=$code;
        $params["Room_Type_Desc"]=$name;
        $params["Room_Rate_Start"]=$priceStart;
        $params["Room_Rate"]=$price;
        $params["LastUpd_Dtm"]= date("Y-m-d h:i:s");
        $params["LastUpd_User_ID"] = $uid;
        $sql ="update mas_room_type 
        set 
            Room_Type_Code=:Room_Type_Code,
            Room_Type_Desc=:Room_Type_Desc,
            Room_Rate_Start=:Room_Rate_Start,
            Room_Rate=:Room_Rate,
            LastUpd_Dtm=:LastUpd_Dtm,
            LastUpd_User_ID=:LastUpd_User_ID
            where SysRoom_Type_ID = :id";
        $res = DB::update($sql, $params);
        return $res;
    }
    public function delRoomType($id){
        $params = array();
        $params["id"] = $id;
        $sql="update mas_room_type set Delete_Flag = 'Y' Where SysRoom_Type_ID = :id";
        $res = DB::update($sql, $params);
        return $res;
    }
    public function getRegularRoom($id){
        $where = array();
        $sql = "SELECT a.SysRoom_ID,a.SysRoom_Type_ID,a.SysParent_Room_ID,a.Floor,a.Room_No,a.Room_Status,a.building_id,b.Room_Type_Desc,
        (SELECT Room_No FROM mas_room WHERE SysRoom_ID = a.SysParent_Room_ID) as ParentRoom 
        FROM mas_room a 
                inner join mas_room_type b on a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.building_id = :id AND b.Room_Type_Code LIKE 'SI%' AND a.Delete_Flag='N' Order by a.Room_No";
        $where["id"] = $id;

        $rooms = DB::select($sql,$where);
        return $rooms;
    }
    public function getVIPRoom($id){
        $where = array();
        $sql = "SELECT a.SysRoom_ID,a.SysRoom_Type_ID,a.SysParent_Room_ID,a.Floor,a.Room_No,a.Room_Status,a.building_id,b.Room_Type_Desc,
        (SELECT Room_No FROM mas_room WHERE SysRoom_ID = a.SysParent_Room_ID) as ParentRoom 
        FROM mas_room a 
                inner join mas_room_type b on a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.building_id = :id AND b.Room_Type_Code LIKE 'VP%' AND a.Delete_Flag='N'  Order by a.Room_No";
        $where["id"] = $id;

        $rooms = DB::select($sql,$where);
        return $rooms;
    }
    public function getSuiteRoom($id){
        $where = array();
        $sql = "SELECT a.SysRoom_ID,a.SysRoom_Type_ID,a.SysParent_Room_ID,a.Floor,a.Room_No,a.Room_Status,a.building_id,b.Room_Type_Desc,
        (SELECT Count(*) FROM mas_room WHERE SysParent_Room_ID = a.SysRoom_ID AND Room_Status = 'IN') as UsedSubCount
        FROM mas_room a 
                inner join mas_room_type b on a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.building_id = :id AND b.Room_Type_Code LIKE 'SU%' AND a.Delete_Flag='N' Order by a.Room_No";
        $where["id"] = $id;

        $rooms = DB::select($sql,$where);
        return $rooms;
    }
    public function getSubSuiteRoomByBuilding($id){
        $where = array();
        $sql = "SELECT a.SysRoom_ID,a.SysRoom_Type_ID,a.SysParent_Room_ID,a.Floor,a.Room_No,a.Room_Status,a.building_id,b.Room_Type_Desc,
        (SELECT Room_No FROM mas_room WHERE SysRoom_ID = a.SysParent_Room_ID) as ParentRoom,
        (SELECT Room_Status FROM mas_room WHERE SysRoom_ID = a.SysParent_Room_ID) as ParentStatus
        FROM mas_room a 
                inner join mas_room_type b on a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.building_id = :id AND b.Room_Type_Code LIKE 'SS%' Order by a.Room_No";
        $where["id"] = $id;

        $rooms = DB::select($sql,$where);
        return $rooms;
    }
    public function getSubSuiteRoom($id){
        $sql = "SELECT a.SysRoom_ID,a.SysRoom_Type_ID,a.SysParent_Room_ID,a.Floor,a.Room_No,a.Room_Status,a.building_id,b.Room_Type_Desc
        FROM mas_room a 
                inner join mas_room_type b on a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.SysParent_Room_ID = :id  Order by a.Room_No";

        $where["id"] = $id;

        $rooms = DB::select($sql,$where);
        return $rooms;
    }
    public function updateStatus($id,$status){
       $sql ="UPDATE mas_room
        SET
            Room_Status = ?
        WHERE SysRoom_ID = ?";
        $res = DB::update($sql, [$status,$id]);
        return $res;
   }
   public function isSuiteRoom($id){
        $res = false;
        $sql = "SELECT Count(*) as cc
        FROM mas_room a 
                inner join mas_room_type b on a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.SysRoom_ID = :id AND b.Room_Type_Code LIKE 'SU%'";

        $where["id"] = $id;

        $rooms = collect(\DB::select($sql,$where))->first();
        if($rooms->cc > 0){
            $res = true;
        }
        return $res;
   }
   public function isSubSuiteRoom($id){
        $res = false;
        $sql = "SELECT Count(*) as cc
        FROM mas_room a 
                inner join mas_room_type b on a.SysRoom_Type_ID=b.SysRoom_Type_ID
        WHERE a.SysRoom_ID = :id AND b.Room_Type_Code LIKE 'SS%'";

        $where["id"] = $id;

        $rooms = collect(\DB::select($sql,$where))->first();
        if($rooms->cc > 0){
            $res = true;
        }
        return $res;
   }
}
