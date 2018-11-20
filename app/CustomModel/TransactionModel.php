<?php

namespace App\CustomModel;

use Illuminate\Support\Facades\DB;
use App\CustomModel\EmployeeModel;
use App\CustomModel\RoomModel;
use App\CustomModel\UtilModel;

class TransactionModel
{
    private $prettyObj;
    private $roomObj;
    private $util;
    private $config;
    function __construct(){
        $this->prettyObj = new EmployeeModel();
        $this->roomObj = new RoomModel();
        $this->util = new UtilModel();
        $this->config = new ConfigModel();
    }
    public function getLastTxnNo($date){
        $lastno = 0;
        $sql = "SELECT max(Txn_No) as LastNo   from tnh_massage where Txn_Date = ?";
        $tmpNo = collect(\DB::select($sql,[$date]))->first();
        if(is_null($tmpNo->LastNo)){
            $lastno = 1;
        }else{
            $lastno = intval($tmpNo->LastNo)+1;
        }

        return $lastno;
    }
    public function getDateTxn(){
        $resDate = "";
        $dbChkin = collect(\DB::select("select Ref_Desc FROM mas_reference WHERE Ref_Type = 'TIME' AND Ref_Code = 'LAST_CHKIN'"))->first();
        $last_checkin_time = $dbChkin->Ref_Desc;
        $dbOpen = collect(\DB::select("select Ref_Desc FROM mas_reference WHERE Ref_Type = 'TIME' AND Ref_Code = 'OPEN'"))->first();
        $open_checkin_time = $dbOpen->Ref_Desc;
        $current_time = date("H:i");

        $int_cur_time = $this->util->timeStringToNumber($current_time);
        $int_open_time = $this->util->timeStringToNumber($open_checkin_time);
        $int_lastchk_time = $this->util->timeStringToNumber($last_checkin_time);
        
        if($int_cur_time >=$int_open_time){
            $resDate = date("Y-m-d");
        }
        else{
            $resDate = date("Y-m-d",time() - 86400);
        }

        return $resDate;
    }
    
    public function timeNearestTenMins(){
        $hour_time = intval(date("H"));
        $min_time = intval(date("i"));
        $mod = $min_time % 5;
        if($mod >=3){
            $min_time = $min_time + (5-$mod);
            if($min_time >= 60){
                $hour_time = $hour_time+1;
                $min_time = 0;
            }
        }
        else{
            $min_time = $min_time - $mod;
        }
        $hour_str = str_pad($hour_time,2,'0',STR_PAD_LEFT);
        $min_str = str_pad($min_time,2,'0',STR_PAD_LEFT);
        return $hour_str.":".$min_str.":00";
    }
    public function Checkin($txn_no,$txn_date,$recept,$cashier){
        $params = array(
            "Txn_Date"=>$txn_date,
            "Txn_No"=>$txn_no,
            "SysReception_ID"=>$recept,
            "Paid_Flag"=>'N',
            "Cancel_Flag"=>'N',
            "LastUpd_Dtm"=>date("Y-m-d H:i:s"),
            "LastUpd_User_ID"=>$cashier,
            "SysCashier_ID"=>$cashier,
        );
        $res = DB::table('tnh_massage')->insertGetId($params);
        return $res;
    }
    public function roomCheckIn($txn_no,$txn_date,$room,$recept,$warnflag,$checkin_time=""){
        $params = array(
            "Txn_Date"=>$txn_date,
            "Txn_No"=>$txn_no,
            "SysRoom_ID"=>$room,
            "SysReception_ID"=>$recept,
            "Warning_Flag"=>$warnflag,
            "Paid_Status"=>'N',
            "Call_Count"=>0,
        );
        if(!empty($checkin_time)){
            $params["Check_In_Time"] = $checkin_time;
        }
        else{
            $params["Check_In_Time"] = date("Y-m-d")." ".$this->timeNearestTenMins();
        }
        $chkSuite = $this->roomObj->isSuiteRoom($room);
        $firstTime = "";
        if($chkSuite){
            $params["Time_Limit"] = $this->config->getFirstSuiteTime();
        }
        $id = DB::table('tnd_massage_room_list')->insertGetId($params);
        $this->roomObj->updateStatus($room,'IN');
        return $id;
    }
     public function prettyCheckIn($txn_no,$txn_date,$room,$pretty,$recept,$checkin_time="",$round=1,$checkout_time=""){
        $pObj = $this->prettyObj->getPretty($pretty);
        $params = array(
            "Txn_Date"=>$txn_date,
            "Txn_No"=>$txn_no,
            "SysRoom_ID"=>$room,
            "SysAngel_ID"=>$pretty,
            "SysReception_ID"=>$recept,
            "Round"=>$round,
            "Round_Time"=>1,
            "Paid_Status"=>'N',
            "Call_Count"=>0,
            "Total_Paid_Amt"=>$pObj->Angel_Fee,
            "Total_Net_Amt"=>$pObj->Angel_Fee,
            "Total_Wage_Amt"=>$pObj->Angel_Wage,
            "Paid_Wage_Status"=>'N'
        );
        if(!empty($checkin_time)){
            $params["Check_In_Time"] = $checkin_time;
        }
        else{
            $params["Check_In_Time"] = date("Y-m-d")." ".$this->timeNearestTenMins();
        }
        if(!empty($checkout_time)){
            $to_time = strtotime($checkout_time);
            $from_time = strtotime($checkin_time);
            $params["Check_Out_Time"] = $checkout_time;
            $params["Time_Amt"] = round(abs($to_time - $from_time) / 60,2);
        }
        $id = DB::table('tnd_massage_angel_list')->insertGetId($params);
        if(empty($checkout_time)){ //no checkout pretty
            $this->prettyObj->updatePrettyStatus($pretty,'WK');
        }
        
        $insRound = array(
            "SysMassage_Angel_List_ID"=>$id,
            "Add_Round_Time"=> date("Y-m-d H:i:s"),
            "Round"=> $round
        );
        DB::table('tnd_massage_angel_add_round')->insert($insRound);
        return $id;
    }
    public function getPrettyCheckinGroupbyRoom($txn,$date,$discheckout=""){
        $sql = "SELECT al.SysRoom_ID,al.SysAngel_ID,a.Angel_ID,a.Nick_Name,al.Check_In_Time,al.Round

FROM tnd_massage_angel_list al
		inner join mas_angel a on al.SysAngel_ID=a.SysAngel_ID
		

WHERE Txn_No = ? AND Txn_Date = ?";
        if(!empty($discheckout)){
            $sql .= " AND al.Check_Out_Time IS NULL";
        }
        $list = DB::select($sql,[$txn,$date]);
        $res = array();
        foreach($list as $key=>$value){
            $res[$value->SysRoom_ID][] = array(
                "id"=>$value->SysAngel_ID,
                "code"=>$value->Angel_ID,
                "name"=>$value->Nick_Name,
                "time"=>$value->Check_In_Time
            );
        }
        return $res;
    }
    public function getPrettyCheckin($txn,$date,$discheckout=""){
        $sql = "SELECT al.SysMassage_Angel_List_ID,al.SysRoom_ID,al.SysAngel_ID,a.Angel_ID,a.Nick_Name,al.Check_In_Time,al.Round,al.Check_Out_Time

FROM tnd_massage_angel_list al
		inner join mas_angel a on al.SysAngel_ID=a.SysAngel_ID
		

WHERE Txn_No = ? AND Txn_Date = ?";
        if(!empty($discheckout)){
            $sql .= " AND al.Check_Out_Time IS NULL";
        }
        $list = DB::select($sql,[$txn,$date]);
        return $list;
    }
    public function getRoomCheckin($txn,$date){
        $sql = "select rl.SysMassage_Room_List_ID,rl.SysRoom_ID,rl.Check_In_Time,rl.Warning_Flag,r.Room_No,rt.Room_Type_Code,rt.Room_Type_Desc,r.SysParent_Room_ID,r.building_id,rc.Reception_ID,rc.Employee_ID,rc.First_Name,rc.Last_Name,rc.SysReception_ID

from tnd_massage_room_list rl
        inner join mas_room r on rl.SysRoom_ID=r.SysRoom_ID
        inner join mas_room_type rt on r.SysRoom_Type_ID=rt.SysRoom_Type_ID
		inner join mas_reception rc on rl.SysReception_ID=rc.SysReception_ID

where Txn_No = ? AND Txn_Date = ? Order by r.Room_No";
        $list = DB::select($sql,[$txn,$date]);

        return $list;
    }
    public function clearRoomAndPretty($txn,$date,$discheckout=""){
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $res = 1;
        $sql = "SELECT SysRoom_ID,SysMassage_Room_List_ID FROM tnd_massage_room_list WHERE Txn_No = ? AND Txn_Date = ?";
        $roomlist = DB::select($sql,[$txn,$date]);
        foreach($roomlist as $key => $value){
            $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
            $del = DB::delete("DELETE FROM tnd_massage_room_list WHERE SysMassage_Room_List_ID = ?",[$value->SysMassage_Room_List_ID]);
            if($del > 0){
                $res = 1;
            }
        }

        $sql = "SELECT SysAngel_ID,SysMassage_Angel_List_ID FROM tnd_massage_angel_list WHERE Txn_No = ? AND Txn_Date = ?";
        if(!empty($discheckout)){
            $sql .= " AND Check_Out_Time IS NULL";
        }
        $prlist = DB::select($sql,[$txn,$date]);
        foreach($prlist as $key => $value){
            $this->prettyObj->updatePrettyStatus($value->SysAngel_ID,'NW');
            $del = DB::delete("DELETE FROM tnd_massage_angel_list WHERE SysMassage_Angel_List_ID = ?",[$value->SysMassage_Angel_List_ID]);
            if($del > 0){
                $res = 1;
                DB::delete("DELETE FROM tnd_massage_angel_add_round WHERE SysMassage_Angel_List_ID = ?",[$value->SysMassage_Angel_List_ID]);
            }
        }

        $del = DB::delete("DELETE FROM tnh_massage WHERE Txn_No = ? AND Txn_Date = ?",[$txn,$date]);
        // $this->roomObj->updateStatus($room,'IN');
        // $this->prettyObj->updatePrettyStatus($pretty,'WK');
        return $res;
    }
    public function getPrettyWorkingList($b1,$b2){
        $sql = "SELECT ta.SysMassage_Angel_List_ID,ta.Txn_Date,ta.Txn_No,ro.Room_No,a.SysAngel_ID,a.Angel_ID,a.Nick_Name as Angel_Nick,rot.Room_Type_Code,ro.SysParent_Room_ID,rot.Room_Type_Desc,re.Reception_ID,ta.Round,re.Nick_Name as Recept_Nick,
        ta.Check_In_Time,(ta.Check_In_Time + INTERVAL at.round_time*ta.Round MINUTE) as Limited_Time,
        ROUND((UNIX_TIMESTAMP((ta.Check_In_Time + INTERVAL at.round_time*ta.Round MINUTE)) - UNIX_TIMESTAMP(SYSDATE()) ) / 60) AS diff_minutes,
        ta.Call_Count,ta.Last_Call_Time,ta.Paid_Status,rm.Warning_Flag,at.round_time,ro.building_id
        FROM tnd_massage_angel_list ta
                    inner join tnh_massage m on ta.Txn_No=m.Txn_No AND ta.Txn_Date=m.Txn_Date
                    inner join tnd_massage_room_list rm on rm.Txn_Date=ta.Txn_Date AND rm.Txn_No=ta.Txn_No AND rm.SysRoom_ID=ta.SysRoom_ID
                    inner join mas_angel a on a.SysAngel_ID = ta.SysAngel_ID
                    inner join mas_angel_type at on a.SysAngelType=at.SysAngelType
                    inner join mas_reception re on ta.SysReception_ID=re.SysReception_ID
                    inner join mas_room ro on ta.SysRoom_ID=ro.SysRoom_ID
                    inner join mas_room_type rot on ro.SysRoom_Type_ID=rot.SysRoom_Type_ID
        WHERE ta.Check_Out_Time IS NULL AND ta.Paid_Status = 'N' AND m.Cancel_Flag = 'N'
        ";
        if(!($b1 && $b2)){
            if($b1){
                $sql .= " AND ro.building_id = 1";
            }
            if($b2){
                $sql .= " AND ro.building_id = 2";
            }
        }
        $sql .=" order by diff_minutes";

        $list = DB::select($sql);
        $res = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            if($value->diff_minutes > $value->round_time){
                if($value->diff_minutes - $value->round_time < 10){
                    $tmp->diff_minutes = $value->round_time;
                }
            }
            if($value->Warning_Flag=='N'){ //ห้ามโทร
                if($value->diff_minutes <= -15){
                    //add round auto
                    $this->addTimePretty($value->SysMassage_Angel_List_ID,0.5);
                }
            }

            if($value->Room_Type_Code == "SS"){
                $parentsRoom = $this->roomObj->getRoom($value->SysParent_Room_ID);
                $tmp->Room_Type_Desc = $tmp->Room_Type_Desc." (".$parentsRoom->Room_No.")";
            }
            $tmp->Room_Type_Desc .= " ".($value->building_id==1 ? "TG":"TA"); 

            $res[] = $tmp;
        }
        return $res;
    }
    public function getPrettyCheckoutList($b1,$b2){
        $sql = "SELECT ta.SysMassage_Angel_List_ID,ta.Txn_Date,ta.Txn_No,ta.Time_Amt,ro.Room_No,a.Angel_ID,a.Nick_Name as Angel_Nick,rot.Room_Type_Code,ro.SysParent_Room_ID,rot.Room_Type_Desc,re.Reception_ID,re.Nick_Name as Recept_Nick,
        ta.Check_In_Time,(ta.Check_In_Time + INTERVAL at.round_time MINUTE) as Limited_Time,
        ROUND((UNIX_TIMESTAMP((ta.Check_In_Time + INTERVAL at.round_time MINUTE)) - UNIX_TIMESTAMP(SYSDATE()) ) / 60) AS diff_minutes,
        ta.Call_Count,ta.Last_Call_Time,ta.Paid_Status,rm.Warning_Flag,ro.building_id
        FROM tnd_massage_angel_list ta
                    inner join tnh_massage m on ta.Txn_No=m.Txn_No AND ta.Txn_Date=m.Txn_Date
                    inner join tnd_massage_room_list rm on rm.Txn_Date=ta.Txn_Date AND rm.Txn_No=ta.Txn_No AND rm.SysRoom_ID=ta.SysRoom_ID
                    inner join mas_angel a on a.SysAngel_ID = ta.SysAngel_ID
                    inner join mas_angel_type at on a.SysAngelType=at.SysAngelType
                    inner join mas_reception re on ta.SysReception_ID=re.SysReception_ID
                    inner join mas_room ro on ta.SysRoom_ID=ro.SysRoom_ID
                    inner join mas_room_type rot on ro.SysRoom_Type_ID=rot.SysRoom_Type_ID
        WHERE ta.Check_Out_Time IS NOT NULL AND ta.Paid_Status = 'N' AND m.Cancel_Flag = 'N'";
        if(!($b1 && $b2)){
            if($b1){
                $sql .= " AND ro.building_id = 1";
            }
            if($b2){
                $sql .= " AND ro.building_id = 2";
            }
        }
        $sql .=" order by diff_minutes";

        $list = DB::select($sql);
        $res = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;

            if($value->Room_Type_Code == "SS"){
                $parentsRoom = $this->roomObj->getRoom($value->SysParent_Room_ID);
                $tmp->Room_Type_Desc = $tmp->Room_Type_Desc." (".$parentsRoom->Room_No.")";
            }
            $tmp->Room_Type_Desc .= " ".($value->building_id==1 ? "TG":"TA"); 

            $res[] = $tmp;
        }
        return $res;
    }
    public function getSuiteWorkingList($b1,$b2){
        $sql = "SELECT rm.SysMassage_Room_List_ID,rm.Txn_No,rm.Txn_Date,ro.Room_No,rot.Room_Type_Desc,re.Reception_ID,re.Nick_Name as Recept_Nick,rm.Check_In_Time,(rm.Check_In_Time + INTERVAL rm.Time_Limit MINUTE) as Limited_Time,ROUND((UNIX_TIMESTAMP((rm.Check_In_Time + INTERVAL rm.Time_Limit MINUTE)) - UNIX_TIMESTAMP(SYSDATE()) ) / 60) AS diff_minutes,rm.Call_Count,rm.Last_Call_Time,rm.Paid_Status,rm.Warning_Flag,rm.Time_Limit

FROM  tnd_massage_room_list rm 
        inner join tnh_massage m on rm.Txn_No=m.Txn_No AND rm.Txn_Date=m.Txn_Date
		inner join mas_reception re on rm.SysReception_ID=re.SysReception_ID
		inner join mas_room ro on rm.SysRoom_ID=ro.SysRoom_ID
		inner join mas_room_type rot on ro.SysRoom_Type_ID=rot.SysRoom_Type_ID
WHERE rm.Check_Out_Time IS NULL AND rm.Paid_Status = 'N' AND m.Cancel_Flag = 'N'  AND rot.Room_Type_Code LIKE 'SU%'";
        if(!($b1 && $b2)){
            if($b1){
                $sql .= " AND ro.building_id = 1";
            }
            if($b2){
                $sql .= " AND ro.building_id = 2";
            }
        }
$sql .=" order by diff_minutes";
        $list = DB::select($sql);
        $res = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            if($value->diff_minutes > $value->Time_Limit){
                $tmp->diff_minutes = $value->Time_Limit;
            }
            $res[] = $tmp;
        }
        return $res;
    }
    public function getSuiteCheckoutList($b1,$b2){
        $sql = "SELECT rm.SysMassage_Room_List_ID,rm.Txn_No,rm.Txn_Date,rm.Time_Amt,ro.Room_No,rot.Room_Type_Desc,re.Reception_ID,re.Nick_Name as Recept_Nick,rm.Check_In_Time,(rm.Check_In_Time + INTERVAL rm.Time_Limit MINUTE) as Limited_Time,ROUND((UNIX_TIMESTAMP((rm.Check_In_Time + INTERVAL rm.Time_Limit MINUTE)) - UNIX_TIMESTAMP(SYSDATE()) ) / 60) AS diff_minutes,rm.Call_Count,rm.Last_Call_Time,rm.Paid_Status,rm.Warning_Flag

FROM  tnd_massage_room_list rm 
        inner join tnh_massage m on rm.Txn_No=m.Txn_No AND rm.Txn_Date=m.Txn_Date
		inner join mas_reception re on rm.SysReception_ID=re.SysReception_ID
		inner join mas_room ro on rm.SysRoom_ID=ro.SysRoom_ID
		inner join mas_room_type rot on ro.SysRoom_Type_ID=rot.SysRoom_Type_ID
WHERE rm.Check_Out_Time IS NOT NULL AND rm.Paid_Status = 'N' AND m.Cancel_Flag = 'N'  AND rot.Room_Type_Code LIKE 'SU%'";
        if(!($b1 && $b2)){
            if($b1){
                $sql .= " AND ro.building_id = 1";
            }
            if($b2){
                $sql .= " AND ro.building_id = 2";
            }
        }
$sql .=" order by diff_minutes";
        $list = DB::select($sql);
        return $list;
    }
    public function addTimePretty($id,$round){
        $sql = "UPDATE tnd_massage_angel_list
                    SET
                        Round = Round + ?
                    WHERE SysMassage_Angel_List_ID = ?
        ";
        $update = DB::update($sql,[$round,$id]);
        // $prData = collect(\DB::select("Select SysAngel_ID FROM tnd_massage_angel_list WHERE SysMassage_Angel_List_ID = ?",[$id]))->first();
        // $pr = $this->prettyObj->getPretty($prData->SysAngel_ID);
        $insRound = array(
            "SysMassage_Angel_List_ID"=>$id,
            "Add_Round_Time"=> date("Y-m-d H:i:s"),
            "Round"=> $round
        );
        DB::table('tnd_massage_angel_add_round')->insert($insRound);
        return $update;
    }
    public function addTimeRoom($id,$round){
        $add_time = $this->config->getNextSuiteTime();
        $time_amt = $round*$add_time;

        $sql = "UPDATE tnd_massage_room_list
                    SET
                        Time_Limit = Time_Limit + ?
                    WHERE SysMassage_Room_List_ID = ?
        ";
        $update = DB::update($sql,[$time_amt,$id]);
        return $update;
    }
    public function editTimeRoom($id,$minute_down){
        $time_amt = $minute_down;

        $sql = "UPDATE tnd_massage_room_list
                    SET
                        Check_In_Time = DATE_ADD(Check_In_Time,INTERVAL ".$time_amt." MINUTE) 
                    WHERE SysMassage_Room_List_ID = ?
        ";
        $update = DB::update($sql,[$id]);
        return $update;
    }
    public function editTimePretty($id,$minute_down){
        $time_amt = $minute_down;

        $sql = "UPDATE tnd_massage_angel_list
                    SET
                        Check_In_Time = DATE_ADD(Check_In_Time,INTERVAL ".$time_amt." MINUTE) 
                    WHERE SysMassage_Angel_List_ID = ?
        ";
        $update = DB::update($sql,[$id]);
        return $update;
    }
    public function cancelBill($txn,$date){
        $sql="UPDATE tnh_massage SET Cancel_Flag = 'Y' WHERE Txn_No = ? AND Txn_Date = ?";
        $upd = DB::update($sql,[$txn,$date]);
        $sql = "SELECT SysRoom_ID,SysMassage_Room_List_ID FROM tnd_massage_room_list WHERE Txn_No = ? AND Txn_Date = ?";
        $roomlist = DB::select($sql,[$txn,$date]);
        foreach($roomlist as $key => $value){
            $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
        }

        $sql = "SELECT SysAngel_ID,SysMassage_Angel_List_ID FROM tnd_massage_angel_list WHERE Txn_No = ? AND Txn_Date = ?";
        $prlist = DB::select($sql,[$txn,$date]);
        foreach($prlist as $key => $value){
            $this->prettyObj->updatePrettyStatus($value->SysAngel_ID,'NW');
        }

        return $upd;
    }
    public function checkoutPretty($id){
        
        $sql = "UPDATE tnd_massage_angel_list
                    SET
                        Check_Out_Time = SYSDATE(),
                        Time_Amt = ROUND((UNIX_TIMESTAMP(SYSDATE()) - UNIX_TIMESTAMP(Check_In_Time) ) / 60)
                    WHERE SysMassage_Angel_List_ID = ?
        ";
        $update = DB::update($sql,[$id]);
        if($update > 0){
            $pretty = "SELECT SysAngel_ID,SysRoom_ID,Txn_No,Txn_Date FROM tnd_massage_angel_list WHERE SysMassage_Angel_List_ID = ?";
            $data = collect(\DB::select($pretty,[$id]))->first();
            $this->prettyObj->updatePrettyStatus($data->SysAngel_ID,'NW'); // update NW
            $room = $this->roomObj->getRoom($data->SysRoom_ID);
            $chkUpdateRoom = true;
            if(!empty($room->SysParent_Room_ID)){
                $roomChk = "SELECT Count(*) as cc FROM tnd_massage_room_list WHERE Txn_No = ? AND Txn_Date = ? AND SysRoom_ID=?";
                $rParentChk = collect(\DB::select($roomChk,[$data->Txn_No,$data->Txn_Date,$room->SysParent_Room_ID]))->first();
                if($rParentChk->cc > 0){
                    $chkUpdateRoom = false;
                }
            }
            
            if($chkUpdateRoom){ //update status room
                $this->roomObj->updateStatus($data->SysRoom_ID,'VA');
            }
        }
        return $update;
    }
    public function checkoutRoom($id){
        $sql = "UPDATE tnd_massage_room_list
                    SET
                        Check_Out_Time = SYSDATE(),
                        Time_Amt = ROUND((UNIX_TIMESTAMP(SYSDATE()) - UNIX_TIMESTAMP(Check_In_Time) ) / 60)
                    WHERE SysMassage_Room_List_ID = ?
        ";
        $update = DB::update($sql,[$id]);
        if($update > 0){
            $room = "SELECT SysRoom_ID,Txn_Date,Txn_No FROM tnd_massage_room_list WHERE SysMassage_Room_List_ID = ?";
            $data = collect(\DB::select($room,[$id]))->first();
            $this->roomObj->updateStatus($data->SysRoom_ID,'VA');
            $roomCheck = $this->roomObj->getSubSuiteRoom($data->SysRoom_ID);
            if(count($roomCheck)>0){
                foreach($roomCheck as $key => $value){
                    $this->roomObj->updateStatus($value->SysRoom_ID,'VA');
                }
            }
            //checkout pretty in room
            $sqlPrettylist = "SELECT SysMassage_Angel_List_ID FROM tnd_massage_angel_list WHERE Txn_Date = ? AND Txn_No = ? AND Check_Out_Time IS NULL";
            $prList = DB::select($sqlPrettylist,[$data->Txn_Date,$data->Txn_No]);
            foreach($prList as $key=>$value){
                $this->checkoutPretty($value->SysMassage_Angel_List_ID);
            }
        }
        return $update;
    }
    public function updateReception($id,$recept){
        $sql = "UPDATE tnd_massage_angel_list SET SysReception_ID = ? WHERE SysMassage_Angel_List_ID = ?";
        $update = DB::update($sql,[$recept,$id]);
        return $update;
    }
    public function moveRoom($id,$room,$type){
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $sql ="";
        if($type=="PR"){
            $sql = "UPDATE tnd_massage_angel_list
                    SET
                        SysRoom_ID = ?
                    WHERE SysMassage_Angel_List_ID = ?";
            
        }else if($type="R"){
            $sql = "UPDATE tnd_massage_room_list
                    SET
                        SysRoom_ID = ?
                    WHERE SysMassage_Room_List_ID = ?";
        }
        $update = DB::update($sql,[$room,$id]);
        $this->roomObj->updateStatus($room,'IN');
    }
    public function delTransRoom($id){
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $sql ="";
        $sql = "DELETE FROM tnd_massage_room_list WHERE SysMassage_Room_List_ID = ? ";
        $update = DB::delete($sql,[$id]);
    }
    public function moveToSuiteRoom($id,$room){
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $checkin_time = date("Y-m-d")." ".$this->timeNearestTenMins();
        $time_limit = $this->config->getFirstSuiteTime();
        $sql ="";
        $sql = "UPDATE tnd_massage_room_list
                    SET
                        SysRoom_ID = ?,
                        Check_In_Time = ?,
                        Time_Limit = ?
                    WHERE SysMassage_Room_List_ID = ?";
        $update = DB::update($sql,[$room,$checkin_time,$time_limit,$id]);
        $this->roomObj->updateStatus($room,'IN');
    }
    public function getHeaderTransaction($txn,$date){
        $sql="SELECT a.*,b.Reception_ID,b.Nick_Name as Recept_Nick,CONCAT(m.First_Name,' ',IFNULL(m.Last_Name,'')) as Member_Name,m.Member_ID,IFNULL(un.Total_Unpaid_Fnb,0) as FNB_Unpaid,
        IFNULL(un.Total_Unpaid_Massage,0) as Mas_Unpaid,IFNULL(mp.FNB_Paid_Amt,0) as FNB_Member,IFNULL(mp.Angel_Paid_Amt,0) as Mas_Member
            FROM tnh_massage a
                    inner join mas_reception b on a.SysReception_ID = b.SysReception_ID
                    left join mas_member m on a.SysMember_ID = m.SysMember_ID
                    left join tnd_massage_unpaid un on a.Txn_Date=un.Txn_Date AND a.Txn_No=un.Txn_No
                    left join tnd_member_payment mp on a.SysMember_ID=mp.SysMember_ID AND a.Txn_Date=mp.Txn_Date AND a.Txn_No=mp.Txn_No
            WHERE a.Txn_No = ? AND a.Txn_Date = ?";
        $header = collect(\DB::select($sql,[$txn,$date]))->first();

        return $header;
    }
    public function getAngelOtFee($id,$txndate){
        $ot_fee = $this->config->getConfig("OT_FEE","ANGEL_FEE");
        $sql = "Select * FROM tnd_massage_angel_list WHERE SysMassage_Angel_List_ID = ?";
        $value = collect(\DB::select($sql,[$id]))->first();
        $pr = $this->prettyObj->getPretty($value->SysAngel_ID);
        $sumOT = 0;
        $roundTime = $pr->round_time;
        $time = strtotime($value->Check_In_Time);
        $over = 0;
        if(floor($value->Round) != $value->Round){ //have 0.5
            $over = 0.5;
        }
        // for($i= $value->Round ; $i > 0; $i-=0.5){
            // $checkin_date = date("Y-m-d",$time);
            // if($checkin_date!=$txndate){
                // $sumOT+=floatval($ot_fee)*0.5;
            // }
            // if($i!=0.5){
                // $time = strtotime('+'.($roundTime*0.5).' minutes', $time);
            // }
        // }
        for($i = ($value->Round-$over);$i > 0;$i--){
            $checkin_date = date("Y-m-d",$time);
            $end_time = strtotime('+'.$roundTime.' minutes', $time);
            if($checkin_date!=$txndate){
                $sumOT+=intval($ot_fee);
            }else{
                if($i < ($value->Round-$over)){ // only add time
                    $chkout_date = date("Y-m-d",$end_time);
                    if($chkout_date!=$txndate){ //end time over 00:00
                        $limit_date = $chkout_date." 00:20:00";
                        $limit_time = strtotime($limit_date);
                        if($time < $limit_time){ //checkin less than 00:20
                            if($end_time > $limit_time){ //end time over than 00:20
                                $sumOT+=intval($ot_fee)/2;
                            }
                        }
                    }
                }
            }
            if($i!=1){
                $time = strtotime('+'.$roundTime.' minutes', $time);
            }
        }
        if($over > 0){
            $time = strtotime('+'.($roundTime*$over).' minutes', $time);
            $checkin_date = date("Y-m-d",$time);
            if($checkin_date!=$txndate){
                $sumOT+=intval($ot_fee*$over);
            }
        }
        return $sumOT;
    }
   
    public function getPrettyListByTxn($txn,$txndate){
        $sql="SELECT a.SysMassage_Angel_List_ID,an.Angel_ID,r.Room_No,a.Check_In_Time,IFNULL(a.Check_Out_Time,a.Check_In_Time + INTERVAL at.round_time MINUTE) as Check_Out_Time,
        at.round_time,a.Round,at.Angel_Fee,at.Angel_Type_Code,IFNULL(a.Angel_Discount_Amt,0) as Angel_Discount_Amt,a.Check_Out_Time as Real_Check_Out,r.building_id,b.building_name
                FROM tnd_massage_angel_list a
                        inner join mas_angel an on a.SysAngel_ID=an.SysAngel_ID
                        inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
                        inner join mas_room r on r.SysRoom_ID=a.SysRoom_ID
                        inner join mas_building b on r.building_id=b.building_id
                WHERE a.Txn_No = ? AND a.Txn_Date = ?";
        $pretty = DB::select($sql,[$txn,$txndate]);
        $res = array();
        foreach($pretty as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            // $checkin_date = date("Y-m-d",strtotime($tmp->Check_In_Time));
            // if($checkin_date!=$txndate){
            //     $ot_fee = $this->config->getConfig("OT_FEE","ANGEL_FEE");
            //     $tmp->Angel_Fee = $tmp->Angel_Fee + intval($ot_fee);
            // }
            // else{

            // }
            if($value->building_id==1){
                $tmp->OT_Fee = $this->getAngelOtFee($tmp->SysMassage_Angel_List_ID,$txndate);
            }else{
                $tmp->OT_Fee = 0;
            }
            
            $res[] = $tmp;
        }

        return $res;
    }
    public function getPrettyByID($id){
        $sql="SELECT a.SysMassage_Angel_List_ID,an.Angel_ID,r.SysRoom_ID,r.Room_No,a.Check_In_Time,IFNULL(a.Check_Out_Time,a.Check_In_Time + INTERVAL at.round_time MINUTE) as Check_Out_Time,
        at.round_time,a.Round,at.Angel_Fee,at.Angel_Type_Code,rc.Reception_ID,rc.Nick_Name as Rc_Nick_Name,b.building_name
                FROM tnd_massage_angel_list a
                        inner join mas_angel an on a.SysAngel_ID=an.SysAngel_ID
                        inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
                        inner join mas_room r on r.SysRoom_ID=a.SysRoom_ID
                        inner join mas_building b on r.building_id = b.building_id
                        inner join mas_reception rc on a.SysReception_ID=rc.SysReception_ID
                WHERE a.SysMassage_Angel_List_ID = ? ";
        $pretty = collect(\DB::select($sql,[$id]))->first();
        if($this->roomObj->isSubSuiteRoom($pretty->SysRoom_ID)){
            $r = $this->roomObj->getRoom($pretty->SysRoom_ID);
            $pretty->Room_No = $r->ParentRoom;
        }
        return $pretty;
    }
    public function getPaymentRoomListByTxn($txn,$txndate){
        $sql ="
                SELECT rl.SysMassage_Room_List_ID,r.Room_No,rt.Room_Type_Desc,rt.Room_Type_Code,rt.Room_Rate,rt.Room_Rate_Start,rl.Check_In_Time,rl.Check_Out_Time,rl.Room_Discount_Amt,rl.Member_Suite_Use,
                IFNULL(rl.Time_Amt,ROUND((UNIX_TIMESTAMP(SYSDATE()) - UNIX_TIMESTAMP(Check_In_Time) ) / 60)) as Time_Amt,b.building_name
                    FROM tnd_massage_room_list rl 
                                inner join mas_room r on r.SysRoom_ID=rl.SysRoom_ID
                                inner join mas_room_type rt on r.SysRoom_Type_ID=rt.SysRoom_Type_ID
                                inner join mas_building b on r.building_id=b.building_id
                    WHERE rl.Txn_No = ? AND rl.Txn_Date = ? AND rt.Room_Rate > 0
        ";
        $roomlist = DB::select($sql,[$txn,$txndate]);
        $suFirst = $this->config->getFirstSuiteTime();
        $suReg = $this->config->getNextSuiteTime();
        $response = array();
        foreach($roomlist as $key=>$value){
            $tmp = new \stdClass();
            $tmp->Check_In_Time = $value->Check_In_Time;
            $tmp->Check_Out_Time = $value->Check_Out_Time;
            $tmp->SysMassage_Room_List_ID = $value->SysMassage_Room_List_ID;
            $tmp->Room_No = $value->Room_No;
            $tmp->Room_Type_Desc = $value->Room_Type_Desc;
            $tmp->Room_Type_Code = $value->Room_Type_Code;
            $tmp->Time = $this->util->minsToHourString($value->Time_Amt);
            $tmp->building_name = $value->building_name;
            $dis_amt = 0;
            $suite_used = 0;
            if(!empty($value->Room_Discount_Amt)){
                $dis_amt = intval($value->Room_Discount_Amt);
            }
            if(!empty($value->Member_Suite_Use)){
                $suite_used = floatval($value->Member_Suite_Use);
                if($suite_used < 0){
                    $suite_used = $suite_used * -1;
                }
                // if($suite_used > 0){
                //     $dis_amt = 0;
                // }
            }
            
            $tmp->Discount = $dis_amt;
            $tmp->Suite_Used = $suite_used;
            if($value->Room_Rate_Start > 0){
                $overRound = 0;
                $tmp->Suite_Flag = 1;
                $fee = $value->Room_Rate_Start;
                $diffTime = $value->Time_Amt - $suFirst;
                if($diffTime > 0){
                    $overRound = (int)($diffTime/$suReg);
                    $overMins = $diffTime % $suReg;
                    if($overMins > 30){
                        $overRound += 0.5;
                    }
                    else if($overMins > 45){
                        $overRound += 1;
                    }
                    $fee += $value->Room_Rate * $overRound;
                }
                $tmp->Rate_Start = $value->Room_Rate_Start;
                $tmp->Rate = $value->Room_Rate;
                $tmp->Over_Round = $overRound;
                $tmp->Fee = $fee;

                $tmp->Suite_Amt = 0;
                // if($suite_used == 0){
                //     $tmp->Suite_Amt = 0;
                // }else if($suite_used > 1){
                //     $tmp->Suite_Amt = $value->Room_Rate_Start + ($overRound*$value->Room_Rate);
                // }else if($suite_used <= 1){
                //     $tmp->Suite_Amt = $value->Room_Rate_Start*$suite_used;
                // }
            }else{ //VIP
                $tmp->Rate_Start = $value->Room_Rate_Start;
                $tmp->Rate = $value->Room_Rate;
                $tmp->Over_Round = 0;
                $tmp->Suite_Flag = 0;
                $tmp->Fee = $value->Room_Rate;
                $tmp->Suite_Amt = 0;
            }
            
            $response[] = $tmp;
        }


        return $response;
    }
    public function savePayment($txn,$txndate,$food,$other,$other_remark,$sum_pretty,$sum_room,$food_cash,$food_credit,$pr_cash,$pr_credit,$member_amt,$paid_status,$member_id){
        $sql ="UPDATE tnh_massage 
                SET FNB_Total_Amt = :food,
                    Other_Charge_Amt = :other,
                    Other_Charge_Remark = :remark,
                    Angel_Total_Amt = :sum_pretty,
                    Room_Total_Amt = :sum_room,
                    FNB_Cash_Amt = :food_cash,
                    FNB_Credit_Amt = :food_credit,
                    Angel_Cash_Amt = :pr_cash,
                    Angel_Credit_Amt = :pr_credit,
                    Member_Fee_Amt = :member_amt,
                    Paid_Flag = :paid_status,
                    SysMember_ID = :member_id
                WHERE Txn_No = :txn AND Txn_Date = :txndate";
        $params = array(
            "txn"=>$txn,
            "txndate"=>$txndate,
            "food"=> empty($food) ? null : $food,
            "other"=>empty($other) ? null : $other,
            "remark"=>$other_remark,
            "sum_pretty"=>empty($sum_pretty) ? null : $sum_pretty,
            "sum_room"=>empty($sum_room) ? null : $sum_room,
            "food_cash"=>empty($food_cash) ? null : $food_cash,
            "food_credit"=>empty($food_credit) ? null : $food_credit,
            "pr_cash"=>empty($pr_cash) ? null : $pr_cash,
            "pr_credit"=>empty($pr_credit) ? null : $pr_credit,
            "paid_status" => $paid_status,
            "member_amt"=>empty($member_amt) ? null : $member_amt,
            "member_id"=>empty($member_id) ? null : $member_id
        );
        $upd = DB::update($sql,$params);
        return $upd;
    }
    public function getTotalUnpaidByTxn($txn,$txndate){
        $sql ="SELECT  SUM(Total_Unpaid) as Total
            FROM tnd_massage_unpaid 
            WHERE Txn_No = ? AND Txn_Date = ?
        ";
        $total = collect(\DB::select($sql,[$txn,$txndate]))->first();

        return $total;
    }
    public function addCreditPayment($params = array()){
        $res = DB::table('tnd_massage_credit_card_list')->insertGetId($params);

        return $res;
    }
    public function addUnpaidPayment($params = array()){
        $res = DB::table('tnd_massage_unpaid')->insertGetId($params);

        return $res;
    }
    public function addMemberPayment($params = array()){
        $res = DB::table('tnd_member_payment')->insertGetId($params);

        return $res;
    }
    public function updatePrettyPayment($id,$round,$paid_amt,$paid_status,$dis_amt,$dis_remark,$ot_fee){
        $sql = "UPDATE tnd_massage_angel_list
                SET
                    `Round` = :round,
                    Total_Paid_Amt = :paid_amt,
                    Total_Net_Amt = :paid_net,
                    Paid_Status = :paid_status,
                    Angel_Discount_Amt = :dis_amt,
                    Angel_Discount_Remark = :dis_remark,
                    OT_Fee = :ot_fee
                WHERE SysMassage_Angel_List_ID = :id
                ";
        $params = array(
            "round"=>$round,
            "id"=>$id,
            "paid_amt"=>empty($paid_amt) ? null : $paid_amt,
            "ot_fee"=>empty($ot_fee) ? null : $ot_fee,
            "paid_net"=>empty($paid_amt) ? null : (intval($paid_amt)-intval($dis_amt)+intval($ot_fee)),
            "paid_status"=>$paid_status,
            "dis_amt"=>empty($dis_amt) ? null : $dis_amt,
            "dis_remark"=>$dis_remark
        );
        $upd = DB::update($sql,$params);
        return $upd;
    }
    public function updateRoomPayment($id,$fee,$dis_amt,$dis_remark,$suite_used){
        $sql = "UPDATE tnd_massage_room_list
                SET
                    Total_Paid_Amt = :fee,
                    Total_Net_Amt = :fee_net,
                    Room_Discount_Amt = :dis_amt,
                    Room_Discount_Remark = :dis_remark,
                    Member_Suite_Use = :suite_used
                WHERE SysMassage_Room_List_ID = :id
                ";
        $params = array(
            "fee"=>$fee,
            "fee_net"=>(intval($fee)-intval($dis_amt)),
            "id"=>$id,
            "dis_amt"=>empty($dis_amt) ? null : $dis_amt,
            "dis_remark"=>$dis_remark,
            "suite_used"=>$suite_used
        );
        $upd = DB::update($sql,$params);
        return $upd;
    }
    public function updateRoomPaidStatus($txn,$txndate,$status){
        $sql = "UPDATE tnd_massage_room_list
                SET
                    Paid_Status = :status
                WHERE Txn_No = :txn AND Txn_Date = :txndate
                ";
        $params = array(
            "status"=>$status,
            "txn"=>$txn,
            "txndate"=>$txndate
        );
        $upd = DB::update($sql,$params);
        return $upd;
    }
    public function telSave($id){
        $sql="UPDATE tnd_massage_angel_list SET Call_Count = Call_Count+1,Last_Call_Time = SYSDATE() Where SysMassage_Angel_List_ID = ?";
        $upd = DB::update($sql,[$id]);
        return $upd;
    }
    public function telRoomSave($id){
        $sql="UPDATE tnd_massage_room_list SET Call_Count = Call_Count+1,Last_Call_Time = SYSDATE() Where SysMassage_Room_List_ID = ?";
        $upd = DB::update($sql,[$id]);
        return $upd;
    }
    public function dailyReport($date,$building=""){
        $params = array();
        $sql ="SELECT AT.Angel_Type_Code,
IFNULL((SELECT SUM(al.Round) FROM tnd_massage_angel_list al inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID where an.SysAngelType=AT.SysAngelType AND (SELECT Cancel_Flag FROM tnh_massage WHERE al.Txn_Date=Txn_Date AND al.Txn_No=Txn_No) != 'Y' AND al.Txn_Date=? ";
        $params[] = $date;
        if(!empty($building)){
            $sql.= " AND r.building_id = ?";
            $params[] = $building;
        }
        $sql .="),0) as Round,";
        $sql .="IFNULL((SELECT SUM(al.Total_Paid_Amt) FROM tnd_massage_angel_list al inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID where an.SysAngelType=AT.SysAngelType AND (SELECT Cancel_Flag FROM tnh_massage WHERE al.Txn_Date=Txn_Date AND al.Txn_No=Txn_No) != 'Y' AND al.Txn_Date=?";
        $params[] = $date;
        if(!empty($building)){
            $sql.= " AND r.building_id = ?";
            $params[] = $building;
        }
        $sql .="),0) as Paid_Amt,";
        $sql .="IFNULL((SELECT SUM(al.Angel_Discount_Amt) FROM tnd_massage_angel_list al inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID where an.SysAngelType=AT.SysAngelType AND (SELECT Cancel_Flag FROM tnh_massage WHERE al.Txn_Date=Txn_Date AND al.Txn_No=Txn_No) != 'Y' AND al.Txn_Date=?";
        $params[] = $date;
        if(!empty($building)){
            $sql.= " AND r.building_id = ?";
            $params[] = $building;
        }
        $sql .="),0) as Discount_Amt,";
        $sql .="IFNULL((SELECT SUM(al.Total_Net_Amt) FROM tnd_massage_angel_list al inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID where an.SysAngelType=AT.SysAngelType AND (SELECT Cancel_Flag FROM tnh_massage WHERE al.Txn_Date=Txn_Date AND al.Txn_No=Txn_No) != 'Y' AND al.Txn_Date=?";
        $params[] = $date;
        if(!empty($building)){
            $sql.= " AND r.building_id = ?";
            $params[] = $building;
        }
        $sql .="),0) as Net_Amt ";

        $sql .="FROM mas_angel_type AT";
        $list = DB::select($sql,$params);

        return $list;
    }
    public function dailyPayReport($date,$building=""){
        $params = array();
        $params[]  = $date;
        $sql="SELECT  SUM(IFNULL(m.Angel_Cash_Amt,0)) as MasCash,SUM(IFNULL(m.FNB_Cash_Amt,0)) as FNBCash,SUM(IFNULL(m.Angel_Credit_Amt,0)) as MasCredit,SUM(IFNULL(m.FNB_Credit_Amt,0)) as FNBCredit,SUM(IFNULL(mem.FNB_Paid_Amt,0)) as FNBMember,SUM(IFNULL(mem.Angel_Paid_Amt,0)) as MasMember,SUM(IFNULL(un.Total_Unpaid,0)) as Unpaid,SUM(IFNULL(mem.Member_Suite_Used,0)) as SuiteUsed

FROM
		tnh_massage m 
			left join tnd_member_payment mem on m.Txn_Date=mem.Txn_Date AND m.Txn_No=mem.Txn_No
			left join tnd_massage_unpaid un on m.Txn_Date=un.Txn_Date AND m.Txn_No=un.Txn_No
			left join tnd_massage_room_list r on r.Txn_Date=m.Txn_Date AND r.Txn_No=m.Txn_No AND (r.Member_Suite_Use = 0 OR r.Member_Suite_Use IS NULL)
            left join mas_room ro on (SELECT SysRoom_ID FROM tnd_massage_angel_list an where m.Txn_Date=an.Txn_Date AND m.Txn_No=an.Txn_No LIMIT 1)=ro.SysRoom_ID
WHERE m.Txn_Date = ? AND m.Cancel_Flag != 'Y'";
        if(!empty($building)){
            $sql.= " AND ro.building_id = ?";
            $params[] = $building;
        }
        $sql .=" GROUP BY m.Txn_Date";
        $data = collect(\DB::select($sql,$params))->first();
        if($data==null){
            $tmp = new \stdClass();
            $tmp->MasCash = 0;
            $tmp->FNBCash = 0;
            $tmp->MasCredit = 0;
            $tmp->FNBCredit = 0;
            $tmp->FNBMember = 0;
            $tmp->MasMember = 0;
            $tmp->Unpaid = 0;
            $tmp->SuiteUsed = 0;

            $data = $tmp;
        }
        return $data;
    }
    public function prettyMonthlyReport($month,$year){
        $sql ="SELECT an.SysAngel_ID,an.Angel_ID
FROM mas_angel an WHERE an.Delete_Flag = 'N'";
        $list = DB::select($sql);
        $response = array();
        foreach($list as $key=>$value){
            $sqlLoop = "SELECT al.Txn_Date,SUM(al.Round) as Round
                FROM tnd_massage_angel_list al
                WHERE al.SysAngel_ID = ? AND MONTH(al.Txn_Date) = ? AND YEAR(al.Txn_Date) = ? AND  al.Paid_Status = 'Y'
                GROUP BY al.Txn_Date
                ORDER BY al.Txn_Date
            ";
            $lstSum = DB::select($sqlLoop,[$value->SysAngel_ID,$month,$year]);
            $arrRound = array();
            foreach($lstSum as $k=>$v){
                $arrRound[$v->Txn_Date] = $v->Round;
            }
            $tmp = new \stdClass();
            $tmp->Angel_ID = $value->Angel_ID;
            $tmp->Txn = $arrRound;
            $response[] = $tmp;
        }

        return $response;
    }
    public function receptMonthlyReport($month,$year){
        $sql ="SELECT rc.SysReception_ID,rc.Reception_ID,rc.Nick_Name
FROM mas_reception rc WHERE rc.Delete_Flag = 'N'";
        $list = DB::select($sql);
        $response = array();
        foreach($list as $key=>$value){
            $sqlLoop = "SELECT al.Txn_Date,SUM(al.Round) as Round
                FROM tnd_massage_angel_list al
                WHERE al.SysReception_ID = ? AND MONTH(al.Txn_Date) = ? AND YEAR(al.Txn_Date) = ? AND  al.Paid_Status = 'Y'
                GROUP BY al.Txn_Date
                ORDER BY al.Txn_Date
            ";
            $lstSum = DB::select($sqlLoop,[$value->SysReception_ID,$month,$year]);
            $arrRound = array();
            foreach($lstSum as $k=>$v){
                $arrRound[$v->Txn_Date] = $v->Round;
            }
            $tmp = new \stdClass();
            $tmp->Reception_ID = $value->Reception_ID;
            $tmp->Nick_Name = $value->Nick_Name;
            $tmp->Txn = $arrRound;
            $response[] = $tmp;
        }

        return $response;
    }
    public function getPaymentListCount($date){
        $sql="select Count(*) as cc
from tnh_massage a
		inner join mas_reception r on a.SysReception_ID = r.SysReception_ID
		left join mas_member m on a.SysMember_ID = m.SysMember_ID
		left join tnd_massage_unpaid tu on a.Txn_Date = tu.Txn_Date AND a.Txn_No=tu.Txn_No
WHERE ((a.Paid_Flag = 'Y' AND tu.Paid_Flag IS NULL) OR (a.Paid_Flag = 'Y' AND tu.Paid_Flag = 'Y')) ";
        if(!empty($date)){
            $sql .="    AND (a.Txn_Date = :date OR tu.Paid_Date = :date1)";
            $where["date"] = $date;
            $where["date1"] = $date;
        }
        $count = collect(\DB::select($sql,$where))->first();
        return $count->cc;
    }
    public function getPaymentList($date,$start=0,$length=10){
        $where = array();
        $sql="select a.Txn_Date,a.Txn_No,tu.Paid_Date,a.LastUpd_Dtm,CONCAT(r.First_Name,' ',IFNULL(r.Last_Name,'')) as Recept_Fullname,m.First_Name as Member_Name
from tnh_massage a
		inner join mas_reception r on a.SysReception_ID = r.SysReception_ID
        left join mas_member m on a.SysMember_ID = m.SysMember_ID
        left join tnd_massage_unpaid tu on a.Txn_Date = tu.Txn_Date AND a.Txn_No=tu.Txn_No
		
WHERE ((a.Paid_Flag = 'Y' AND tu.Paid_Flag IS NULL) OR (a.Paid_Flag = 'Y' AND tu.Paid_Flag = 'Y'))";
        if(!empty($date)){
            $sql .="    AND (a.Txn_Date = :date OR tu.Paid_Date = :date1)";
            $where["date"] = $date;
            $where["date1"] = $date;
        }
        $sql .=" Order by a.LastUpd_Dtm desc";
        $sql .=" LIMIT ".$start.",".$length;
        $list = DB::select($sql,$where);
        $resList = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            $str_room = "";
            $rList = $this->getRoomCheckin($value->Txn_No,$value->Txn_Date);
            foreach($rList as $kr=>$vr){
                $str_room .=$vr->Room_No." ";
            }
            $tmp->room = $str_room;
            $str_pr = "";
            $prList = $this->getPrettyCheckin($value->Txn_No,$value->Txn_Date);
            foreach($prList as $kp=>$vp){
                $str_pr .= $vp->Angel_ID." ";
            }
            $tmp->pretty = $str_pr;
            $resList[] = $tmp;
        }
        return $resList;
    }
    public function getPaymentDebtListCount($recept,$date){
        $where = array();
        $sql="select Count(*) as cc
FROM tnh_massage m
		inner join tnd_massage_unpaid un on m.Txn_Date=un.Txn_Date AND m.Txn_No=un.Txn_No
		inner join mas_reception rc on m.SysReception_ID = rc.SysReception_ID

WHERE un.Paid_Flag = 'N'";
        if(!empty($recept)){
            $sql .="    AND rc.SysReception_ID = :recept";
            $where["recept"] = $recept;
        }
        if(!empty($date)){
            $sql .="    AND m.Txn_Date = :date";
            $where["date"] = $date;
        }
        $count = collect(\DB::select($sql,$where))->first();
        return $count->cc;
    }
    public function getPaymentDebtList($recept,$date,$start=0,$length=10){
        $where = array();
        $sql ="SELECT m.Txn_Date,m.Txn_No,rc.SysReception_ID,CONCAT(rc.First_Name,' ',IFNULL(rc.Last_Name,'')) as Recept_Name,un.Remark,un.Total_Unpaid

FROM tnh_massage m
		inner join tnd_massage_unpaid un on m.Txn_Date=un.Txn_Date AND m.Txn_No=un.Txn_No
		inner join mas_reception rc on m.SysReception_ID = rc.SysReception_ID

WHERE un.Paid_Flag = 'N'";
        if(!empty($recept)){
            $sql .="    AND rc.SysReception_ID = :recept";
            $where["recept"] = $recept;
        }
        if(!empty($date)){
            $sql .="    AND m.Txn_Date = :date";
            $where["date"] = $date;
        }
        $sql .= " Order By m.Txn_Date desc,m.Txn_No desc";
        $sql .=" LIMIT ".$start.",".$length;
        $list = DB::select($sql,$where);

        return $list;
    }
    public function payUnpaid($txn,$date,$cash,$credit,$member){
        $sql = "UPDATE tnd_massage_unpaid
            SET
                Cash_Amt = ?,
                Credit_Amt = ?,
                Member_Amt = ?,
                Paid_Flag = 'Y',
                Paid_Date = SYSDATE()
            WHERE 
                Txn_Date = ? AND Txn_No = ?
        ";
        $upd = DB::update($sql,[$cash,$credit,$member,$date,$txn]);
        return $upd;
    }
}
