<?php

namespace App\CustomModel;

use Illuminate\Support\Facades\DB;
use App\CustomModel\ConfigModel;

class EmployeeModel
{
    private $config;
    private $tranObj;
    function __construct(){
        $this->config = new ConfigModel();
    }
    public function getReceptList($empcode,$nickname,$name,$lname,$start=0,$length=10){
        $where = array();
        $sql = "SELECT *
    FROM mas_reception a
        WHERE a.Delete_Flag = 'N'";
       if(!empty($empcode)){
            $sql .=" AND a.Employee_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
           $sql .=" AND a.Nick_Name LIKE :nickname";
           $where["nickname"] = "%".$nickname."%";
       }
       if(!empty($name)){
           $sql .=" AND a.First_Name LIKE :name";
           $where["name"]="%".$name."%";
       }
       if(!empty($lname)){
           $sql .=" AND a.Last_Name LIKE :lname";
           $where["lname"]="%".$lname."%";
       }
       $sql .= " Order by a.Reception_ID";
       $sql .= " LIMIT ".$start.",".$length;
        $userlist = DB::select($sql,$where);
        return $userlist;
    }
    public function getReceptCount($empcode,$nickname,$name,$lname){
        $where = array();
        $sql = "SELECT Count(*) as cc
        FROM mas_reception a
        WHERE a.Delete_Flag = 'N'";
       if(!empty($empcode)){
            $sql .=" AND a.Employee_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
           $sql .=" AND a.Nick_Name LIKE :nickname";
           $where["nickname"] = "%".$nickname."%";
       }
       if(!empty($name)){
           $sql .=" AND a.First_Name LIKE :name";
           $where["name"]="%".$name."%";
       }
       if(!empty($lname)){
           $sql .=" AND a.Last_Name LIKE :lname";
           $where["lname"]="%".$lname."%";
       }
       $count = collect(\DB::select($sql,$where))->first();
       return $count->cc;
    }
    public function getReceptType(){
        $sql="SELECT * FROM mas_reception_type";
        $list = DB::select($sql);
        return $list;
    }
    public function getReception($id){
        $sql="SELECT * FROM mas_reception WHERE SysReception_ID = ?";
        $recept = collect(\DB::select($sql,[$id]))->first();
        return $recept;
    }
    public function addReception($params = array()){
         $id = DB::table('mas_reception')->insertGetId($params);
        return $id;
    }
    public function editReception($params = array()){
        $sql = "UPDATE mas_reception
            SET
                SysReception_Type_ID = :SysReception_Type_ID,
                Reception_ID =:Reception_ID,
                Employee_ID =:Employee_ID,
                First_Name = :First_Name,
                Last_Name = :Last_Name,
                Nick_Name = :Nick_Name,
                Citizen_ID = :Citizen_ID,
                Address = :Address,
                Tel_No = :Tel_No,
                LastUpd_Dtm = :LastUpd_Dtm,
                LastUpd_User_ID = :LastUpd_User_ID
            WHERE SysReception_ID = :id
        ";
        $res = DB::update($sql,$params);
        return $res;
    }
     public function delRecept($id){
        $params = array();
        $params["id"] = $id;
        $sql="update mas_reception set Delete_Flag = 'Y',Work_To_Date=SYSDATE() Where SysReception_ID = :id";
        $res = DB::update($sql, $params);
        return $res;
    }
    public function addPrettyPerformance($params = array()){
        $id = DB::table('tnd_angel_performance')->insert($params);
        return $id;
    }
    public function updatePrettyPerformance($params = array()){
        $sql="update tnd_angel_performance 
            set 
                Total_Fee_Amt = Total_Fee_Amt + :Total_Fee_Amt,
                Total_Wage_Amt = Total_Wage_Amt + :Total_Wage_Amt,
                Total_Commission_Amt = Total_Commission_Amt + :Total_Commission_Amt,
                Make_Up_Amt = Make_Up_Amt + :Make_Up_Amt,
                Total_Round = Total_Round + :Total_Round,
                Total_Receive = Total_Receive + :Total_Receive,
                Other_Debt = Other_Debt + :Other_Debt,
                Other_Debt_Remark = Other_Debt_Remark + :Other_Debt_Remark,
                Other_Income = Other_Debt + :Other_Income,
                Other_Income_Remark = Other_Debt_Remark + :Other_Income_Remark,
                Pay_Debt1 = Pay_Debt1 + :Pay_Debt1,
                Pay_Debt2 = Pay_Debt2 + :Pay_Debt2,
                Pay_Debt3 = Pay_Debt3 + :Pay_Debt3,
                Pay_Debt4 = Pay_Debt4 + :Pay_Debt4,
                Pay_Debt5 = Pay_Debt5 + :Pay_Debt5,
                LastUpd_User_ID = :LastUpd_User_ID,
                LastUpd_Dtm = :LastUpd_Dtm
        
        Where SysAngel_ID = :SysAngel_ID AND Txn_Date = :Txn_Date";
        $res = DB::update($sql, $params);
        return $res;
    }
//     public function updatePrettyWage($id,$date){
//         $sql ="SELECT  al.SysMassage_Angel_List_ID,al.Txn_Date,al.Txn_No,IFNULL(m.Angel_Cash_Amt,0) as Cash_Type,IFNULL(m.Angel_Credit_Amt,0) as Credit_Type,
//         IFNULL(m.Member_Fee_Amt,0) as Member_Type,IFNULL((SELECT SUM(Total_Unpaid_Massage) FROM tnd_massage_unpaid WHERE Txn_Date=al.Txn_Date AND Txn_No=al.Txn_No),0) as Unpaid_Type,
//         r.Room_No,al.Check_In_Time,al.Time_Amt,al.Round,(al.Round*at.Angel_Wage) as Wage_Amt,(al.Round*at.Credit_Comm) as Comm
// FROM	tnh_massage m
// 			inner join tnd_massage_angel_list al on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
// 			inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
// 			inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
// 			inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID

// WHERE al.SysAngel_ID =  ? AND m.Txn_Date = ?";
//         $list = DB::select($sql,[$id,$date]);
//         foreach($list as $key=>$value){
//             $total = $value->Wage_Amt - $value->Comm;
//             DB::update("UPDATE tnd_massage_angel_list SET Paid_Wage_Status='Y',Total_Wage_Amt = ? WHERE SysMassage_Angel_List_ID = ?",[$total,$value->SysMassage_Angel_List_ID]);
//         }
//         return 1;
//     }
    public function updatePrettyWage($idlist){
        $sql ="SELECT  al.SysMassage_Angel_List_ID,al.Txn_Date,al.Txn_No,IFNULL(m.Angel_Cash_Amt,0) as Cash_Type,IFNULL(m.Angel_Credit_Amt,0) as Credit_Type,
        IFNULL(m.Member_Fee_Amt,0) as Member_Type,IFNULL((SELECT SUM(Total_Unpaid_Massage) FROM tnd_massage_unpaid WHERE Txn_Date=al.Txn_Date AND Txn_No=al.Txn_No),0) as Unpaid_Type,
        r.Room_No,al.Check_In_Time,al.Time_Amt,al.Round,(al.Round*at.Angel_Wage) as Wage_Amt,(al.Round*at.Credit_Comm) as Comm
FROM	tnh_massage m
            inner join tnd_massage_angel_list al on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
            inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
            inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
            inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID

WHERE al.SysMassage_Angel_List_ID IN (".$idlist.")";
        $list = DB::select($sql);
        foreach($list as $key=>$value){
            $total = $value->Wage_Amt - $value->Comm;
            DB::update("UPDATE tnd_massage_angel_list SET Paid_Wage_Status='Y',Total_Wage_Amt = ? WHERE SysMassage_Angel_List_ID = ?",[$total,$value->SysMassage_Angel_List_ID]);
        }
        return 1;
    }
    public function keepHistoryWage($txndate,$aid,$income,$income_remark,$charge,$charge_remark,$makeup,$total,$angel_list,$ot_list){
        $wobj = collect(\DB::select("SELECT COUNT(*) as cc FROM mas_wage_history WHERE Txn_Date = ? AND SysAngel_ID = ?",[$txndate,$aid]))->first();
        $wcount = $wobj->cc + 1;
        $params = array(
            "Txn_Date"=>$txndate,
            "SysAngel_ID"=>$aid,
            "Wage_Count"=>$wcount,
            "Action_Date"=>date("Y-m-d H:i:s"),
            "Income_Amt"=>$income,
            "Income_Remark"=>$income_remark,
            "Charge_Amt"=>$charge,
            "Charge_Remark"=>$charge_remark,
            "Makeup_Amt"=>$makeup,
            "Total_Amt"=>$total
        );
        $id = DB::table('mas_wage_history')->insertGetId($params);
        if($id > 0){ 
            //add detail
            $sql ="SELECT  al.SysMassage_Angel_List_ID,al.Txn_Date,al.Txn_No,IFNULL(m.Angel_Cash_Amt,0) as Cash_Type,IFNULL(m.Angel_Credit_Amt,0) as Credit_Type,
            IFNULL(m.Member_Fee_Amt,0) as Member_Type,IFNULL((SELECT SUM(Total_Unpaid_Massage) FROM tnd_massage_unpaid WHERE Txn_Date=al.Txn_Date AND Txn_No=al.Txn_No),0) as Unpaid_Type,
            r.Room_No,al.Check_In_Time,al.Time_Amt,al.Round,(al.Round*at.Angel_Wage) as Wage_Amt,(al.Round*at.Credit_Comm) as Comm,al.SysRoom_ID,r.building_id
    FROM	tnh_massage m
                inner join tnd_massage_angel_list al on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
                inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
                inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
                inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID

    WHERE al.SysMassage_Angel_List_ID IN (".$angel_list.")";
            $list = DB::select($sql);
            $sumCard = 0;
			$otArr = explode(',',$ot_list);
            foreach($list as $key=>$value){
                $otfee=0;
                if($value->building_id==1){
                    $otfee = $this->getAngelOtWage($value->SysMassage_Angel_List_ID,$txndate);
                }
                
                $paramsDetail = array(
                    "SysPayWage_ID"=>$id,
                    "Check_In_Time"=>$value->Check_In_Time,
                    "SysRoom_ID"=>$value->SysRoom_ID,
                    "Round"=>$value->Round,
                    "OT_Fee"=>$otArr[$key],
                    "Total_Amt"=>($value->Wage_Amt + $otArr[$key])
                );
                 $sub_id = DB::table('mas_wage_history_detail')->insertGetId($paramsDetail);
                $sumCard += $value->Comm;
            }

            //update card amt
            DB::update("UPDATE mas_wage_history SET Card_Amt = ? WHERE SysPayWage_ID = ?",[$sumCard,$id]);
        }

        return $id;
    }
    public function getHistoryWage($id){
        $sql = "SELECT * FROM mas_wage_history WHERE SysPayWage_ID = ?";
        $tmp = collect(\DB::select($sql,[$id]))->first();
        $sqlsub = "SELECT d.*,r.Room_No
            FROM mas_wage_history_detail  d
                    inner join mas_room r on d.SysRoom_ID=r.SysRoom_ID
            WHERE d.SysPayWage_ID = ?";
        $tmp->Detail_List = DB::select($sqlsub,[$id]);
        return $tmp;
    }
    public function getPrettyWagePerson($id,$date,$building=""){
        $sql ="SELECT  al.SysMassage_Angel_List_ID,al.Txn_Date,al.Txn_No,IFNULL(m.Angel_Cash_Amt,0) as Cash_Type,IFNULL(m.Angel_Credit_Amt,0) as Credit_Type,
        IFNULL(m.Member_Fee_Amt,0) as Member_Type,IFNULL((SELECT SUM(Total_Unpaid_Massage) FROM tnd_massage_unpaid WHERE Txn_Date=al.Txn_Date AND Txn_No=al.Txn_No),0) as Unpaid_Type,
        r.Room_No,al.Check_In_Time,al.Time_Amt,al.Round,(al.Round*at.Angel_Wage) as Wage_Amt,(al.Round*at.Credit_Comm) as Comm,r.building_id
FROM	tnh_massage m
			inner join tnd_massage_angel_list al on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
			inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
			inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
			inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID

WHERE al.SysAngel_ID =  ? AND m.Txn_Date = ? AND al.Paid_Wage_Status = 'N' AND m.Cancel_Flag = 'N'";
    $params = array();
    $params[] = $id;
    $params[] = $date;
        if(!empty($building)){
            $sql .= " AND r.building_id = ?";
            $params[] = $building;
        }
        $list = DB::select($sql,$params);
        $res = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            // $checkin_date = date("Y-m-d",strtotime($tmp->Check_In_Time));
            // if($checkin_date!=$date){
            //     $ot_wage = $this->config->getConfig("OT_WAGE","ANGEL_FEE");
            //     $tmp->Wage_Amt = $tmp->Wage_Amt + intval($ot_wage);
            // }
            if($value->building_id==1){
                $tmp->OT_Wage = $this->getAngelOtWage($value->SysMassage_Angel_List_ID,$date);
            }else{
                $tmp->OT_Wage = 0;
            }
            
            $res[] = $tmp;
        }
        return $res;
    }
     public function getAngelOtWage($id,$txndate){
        $ot_fee = $this->config->getConfig("OT_WAGE","ANGEL_FEE");
        $sql = "Select * FROM tnd_massage_angel_list WHERE SysMassage_Angel_List_ID = ?";
        $value = collect(\DB::select($sql,[$id]))->first();
        $pr = $this->getPretty($value->SysAngel_ID);
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
            }
            else{
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
    public function getPrettyWageRecPerson($id,$date,$building=""){
        $sql ="SELECT  al.SysMassage_Angel_List_ID,al.Txn_Date,al.Txn_No,IFNULL(m.Angel_Cash_Amt,0) as Cash_Type,IFNULL(m.Angel_Credit_Amt,0) as Credit_Type,
        IFNULL(m.Member_Fee_Amt,0) as Member_Type,IFNULL((SELECT SUM(Total_Unpaid_Massage) FROM tnd_massage_unpaid WHERE Txn_Date=al.Txn_Date AND Txn_No=al.Txn_No),0) as Unpaid_Type,
        r.Room_No,al.Check_In_Time,al.Time_Amt,al.Round,(al.Round*at.Angel_Wage) as Wage_Amt,(al.Round*at.Credit_Comm) as Comm,r.building_id
FROM	tnh_massage m
			inner join tnd_massage_angel_list al on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
			inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
			inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
			inner join mas_room r on al.SysRoom_ID = r.SysRoom_ID

WHERE al.SysAngel_ID =  ? AND m.Txn_Date = ? AND al.Paid_Wage_Status = 'Y' AND m.Cancel_Flag = 'N'";
        $list = DB::select($sql,[$id,$date]);
        $res = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            if($value->building_id==1){
                $tmp->OT_Wage = $this->getAngelOtWage($value->SysMassage_Angel_List_ID,$date);
            }
            else{
                $tmp->OT_Wage = 0;
            }
            
            $res[] = $tmp;
        }
        return $res;
    }
    public function getPrettyPerformance($id,$date){
        $sql="SELECT Total_Fee_Amt,Total_Wage_Amt,Total_Commission_Amt,Make_Up_Amt,Total_Round,Total_Receive,IFNULL(Pay_Debt1,0) as  Pay_Debt1,IFNULL(Pay_Debt2,0) as  Pay_Debt2
        ,IFNULL(Pay_Debt3,0) as  Pay_Debt3,IFNULL(Pay_Debt4,0) as  Pay_Debt4,IFNULL(Pay_Debt5,0) as  Pay_Debt5,IFNULL(Other_Debt,0) as Other_Debt,IFNULL(Other_Income,0) as Other_Income,IFNULL(Other_Debt_Remark,0) as Other_Debt_Remark
        
            FROM tnd_angel_performance WHERE SysAngel_ID =  ? AND Txn_Date = ?";
        $data = collect(\DB::select($sql,[$id,$date]))->first();

        return $data;
    }
    public function getPrettyWageList($txndate,$empcode,$nickname,$building,$start=0,$length=10){
        $where = array();
        $sql="
            SELECT al.SysAngel_ID,an.Angel_ID,al.Txn_Date,SUM(al.Round) as Round,Sum(al.Round*at.Angel_Wage) as Wage_Amt,SUM(al.Round*at.Credit_Comm) as Comm,(SELECT Ref_Desc FROM mas_reference WHERE Ref_Code='MAKEUP' AND Ref_Type='ANGEL_PAYMENT') as Makeup,IFNULL(ap.Pay_Debt1,0)+IFNULL(ap.Pay_Debt2,0)+IFNULL(ap.Pay_Debt3,0)+IFNULL(ap.Pay_Debt4,0)+IFNULL(ap.Pay_Debt5,0)+IFNULL(ap.Other_Debt,0) as Debt,IFNULL(ap.Total_Receive,0) as Receive
FROM tnd_massage_angel_list al
            inner join tnh_massage m on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
			inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
            inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
            inner join mas_room r on r.SysRoom_ID = al.SysRoom_ID
			left join tnd_angel_performance ap on al.Txn_Date=ap.Txn_Date AND al.SysAngel_ID=ap.SysAngel_ID
WHERE  m.Cancel_Flag = 'N' AND al.Paid_Wage_Status = 'N'";
        if(!empty($txndate)){
            $sql .=" AND al.Txn_Date = :txndate";
            $where["txndate"]= $txndate;
       }
        if(!empty($empcode)){
            $sql .=" AND an.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
            $sql .=" AND an.Nick_Name LIKE :nickname";
            $where["nickname"]= "%".$nickname."%";
       }
       if(!empty($building)){
           $sql.=" AND r.building_id = :building";
           $where["building"] = $building;
       }


        $sql .=" GROUP BY al.SysAngel_ID,al.Txn_Date,an.Angel_ID,ap.Total_Receive,ap.Pay_Debt1,ap.Pay_Debt2,ap.Pay_Debt3,ap.Pay_Debt4,ap.Pay_Debt5,ap.Other_Debt Order BY al.Txn_Date DESC";
       $sql .= " LIMIT ".$start.",".$length;
        $list = DB::select($sql,$where);
        return $list;
    }
    public function getPrettyWageCount($txndate,$empcode,$nickname,$building){
        $where = array();
        $sql="
            SELECT al.SysAngel_ID,an.Angel_ID,al.Txn_Date,SUM(al.Round) as Round,Sum(al.Round*at.Angel_Wage) as Wage_Amt,SUM(al.Round*at.Credit_Comm) as Comm,(SELECT Ref_Desc FROM mas_reference WHERE Ref_Code='MAKEUP' AND Ref_Type='ANGEL_PAYMENT') as Makeup,IFNULL(ap.Pay_Debt1,0)+IFNULL(ap.Pay_Debt2,0)+IFNULL(ap.Pay_Debt3,0)+IFNULL(ap.Pay_Debt4,0)+IFNULL(ap.Pay_Debt5,0) as Debt,IFNULL(ap.Total_Receive,0) as Receive
FROM tnd_massage_angel_list al
            inner join tnh_massage m on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
			inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
            inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
            inner join mas_room r on r.SysRoom_ID = al.SysRoom_ID
			left join tnd_angel_performance ap on al.Txn_Date=ap.Txn_Date AND al.SysAngel_ID=ap.SysAngel_ID
WHERE  m.Cancel_Flag = 'N' AND al.Paid_Wage_Status = 'N'";
        if(!empty($txndate)){
            $sql .=" AND al.Txn_Date = :txndate";
            $where["txndate"]= $txndate;
       }
        if(!empty($empcode)){
            $sql .=" AND an.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
            $sql .=" AND an.Nick_Name LIKE :nickname";
            $where["nickname"]= "%".$nickname."%";
       }
       if(!empty($building)){
           $sql.=" AND r.building_id = :building";
           $where["building"] = $building;
       }
        $sql .=" GROUP BY al.SysAngel_ID,al.Txn_Date,an.Angel_ID,ap.Total_Receive,ap.Pay_Debt1,ap.Pay_Debt2,ap.Pay_Debt3,ap.Pay_Debt4,ap.Pay_Debt5 Order BY al.Txn_Date DESC";
       $count = count(DB::select($sql,$where));
       if(!is_null($count)){
          return $count;
       }else{
            return 0;
       }
    }
    public function getPrettyWageRecList($txndate,$empcode,$nickname,$building,$start=0,$length=10){
        $where = array();
        $sql="
            SELECT al.SysAngel_ID,an.Angel_ID,al.Txn_Date,SUM(al.Round) as Round,Sum(al.Round*at.Angel_Wage) as Wage_Amt,SUM(al.Round*at.Credit_Comm) as Comm,IFNULL(ap.Make_Up_Amt,0) as Makeup,IFNULL(ap.Pay_Debt1,0)+IFNULL(ap.Pay_Debt2,0)+IFNULL(ap.Pay_Debt3,0)+IFNULL(ap.Pay_Debt4,0)+IFNULL(ap.Pay_Debt5,0)+IFNULL(ap.Other_Debt,0) as Debt,IFNULL(ap.Total_Receive,0) as Receive
FROM tnd_massage_angel_list al
            inner join tnh_massage m on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
			inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
            inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
            inner join mas_room r on r.SysRoom_ID = al.SysRoom_ID
			left join tnd_angel_performance ap on al.Txn_Date=ap.Txn_Date AND al.SysAngel_ID=ap.SysAngel_ID
WHERE  m.Cancel_Flag = 'N' AND al.Paid_Wage_Status = 'Y'";
        if(!empty($txndate)){
            $sql .=" AND al.Txn_Date = :txndate";
            $where["txndate"]= $txndate;
       }
        if(!empty($empcode)){
            $sql .=" AND an.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
            $sql .=" AND an.Nick_Name LIKE :nickname";
            $where["nickname"]= "%".$nickname."%";
       }
       if(!empty($building)){
           $sql.=" AND r.building_id = :building";
           $where["building"] = $building;
       }

        $sql .=" GROUP BY al.SysAngel_ID,al.Txn_Date,an.Angel_ID,ap.Total_Receive,ap.Pay_Debt1,ap.Pay_Debt2,ap.Pay_Debt3,ap.Pay_Debt4,ap.Pay_Debt5,ap.Other_Debt,ap.Make_Up_Amt Order BY al.Txn_Date DESC";
       $sql .= " LIMIT ".$start.",".$length;
        $list = DB::select($sql,$where);
        return $list;
    }
    public function getPrettyWageRecCount($txndate,$empcode,$nickname,$building){
        $where = array();
        $sql="
            SELECT al.SysAngel_ID,an.Angel_ID,al.Txn_Date,SUM(al.Round) as Round,Sum(al.Round*at.Angel_Wage) as Wage_Amt,SUM(al.Round*at.Credit_Comm) as Comm,IFNULL(ap.Make_Up_Amt,0) as Makeup,IFNULL(ap.Pay_Debt1,0)+IFNULL(ap.Pay_Debt2,0)+IFNULL(ap.Pay_Debt3,0)+IFNULL(ap.Pay_Debt4,0)+IFNULL(ap.Pay_Debt5,0) as Debt,IFNULL(ap.Total_Receive,0) as Receive
FROM tnd_massage_angel_list al
            inner join tnh_massage m on m.Txn_Date = al.Txn_Date AND m.Txn_No=al.Txn_No
			inner join mas_angel an on al.SysAngel_ID=an.SysAngel_ID
            inner join mas_angel_type at on an.SysAngelType=at.SysAngelType
            inner join mas_room r on r.SysRoom_ID = al.SysRoom_ID
			left join tnd_angel_performance ap on al.Txn_Date=ap.Txn_Date AND al.SysAngel_ID=ap.SysAngel_ID
WHERE  m.Cancel_Flag = 'N' AND al.Paid_Wage_Status = 'Y'";
        if(!empty($txndate)){
            $sql .=" AND al.Txn_Date = :txndate";
            $where["txndate"]= $txndate;
       }
        if(!empty($empcode)){
            $sql .=" AND an.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
            $sql .=" AND an.Nick_Name LIKE :nickname";
            $where["nickname"]= "%".$nickname."%";
       }
       if(!empty($building)){
           $sql.=" AND r.building_id = :building";
           $where["building"] = $building;
       }
        $sql .=" GROUP BY al.SysAngel_ID,al.Txn_Date,an.Angel_ID,ap.Total_Receive,ap.Pay_Debt1,ap.Pay_Debt2,ap.Pay_Debt3,ap.Pay_Debt4,ap.Pay_Debt5,ap.Make_Up_Amt Order BY al.Txn_Date DESC";
       $count = count(DB::select($sql,$where));
       if(!is_null($count)){
          return $count;
       }else{
            return 0;
       }
    }
    public function getPrettyWorkingTable($txndate,$type){
        $params = array($txndate,$txndate,$txndate);
        $sql="select a.SysAngel_ID,a.Angel_ID,a.SysAngelType,a.Angel_Status,
IFNULL((SELECT SUM(Round_Time) FROM tnd_massage_angel_list WHERE SysAngel_ID=a.SysAngel_ID AND Txn_Date = ? Group By SysAngel_ID),0) as StartRound,
IFNULL((SELECT SUM(Round-Round_Time) FROM tnd_massage_angel_list WHERE SysAngel_ID=a.SysAngel_ID AND Txn_Date = ? Group By SysAngel_ID),0) as AddRound,
rmap.SysRFID_ID,rtime.Time_ComeIn,rtime.Time_ComeOut

from mas_angel a
		left join mas_rfid_map rmap on a.SysAngel_ID=rmap.SysObject_ID AND Object_Type = 'AN'
		left join mas_rfid_time rtime on rmap.SysRFID_ID=rtime.SysRFID_ID AND rtime.Txn_Date = ?
		
WHERE a.Delete_Flag = 'N'";
        if(!empty($type)){
            $sql .= " AND a.SysAngelType = ?";
            $params[] = $type;
        }
$sql .="Order by a.SysAngelType,a.Angel_ID";
        $list = DB::select($sql,$params);
        $resList = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            $arrLap = array();
            for($i=0;$i<11;$i++){
                $arrLap[] = "";
            }
            $sqlList = "SELECT rec.Reception_ID,al.Check_In_Time,al.Round
FROM tnd_massage_angel_list al
		inner join mas_reception rec on al.SysReception_ID=rec.SysReception_ID

WHERE al.Txn_Date = ? AND al.SysAngel_ID = ? Order By al.Check_In_Time LIMIT 10";
            $lapList = DB::select($sqlList,[$txndate,$value->SysAngel_ID]);
            foreach ($lapList as $k => $v) {
                $arrLap[$k] = date("H:i",strtotime($v->Check_In_Time))."<br/>".$v->Reception_ID."/".$v->Round;
            }
            $tmp->lap = $arrLap;
            $resList[$value->SysAngelType][] = $tmp;
        }
        return $resList;
    }
    public function getPrettyList($empcode,$nickname,$name,$lname,$type,$status,$start=0,$length=10){
        $where = array();
        $sql = "SELECT a.*,b.Angel_Type_Code
    FROM mas_angel a
            inner join mas_angel_type b on a.SysAngelType=b.SysAngelType
        WHERE a.Delete_Flag = 'N'";
       if(!empty($empcode)){
            $sql .=" AND a.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
           $sql .=" AND a.Nick_Name LIKE :nickname";
           $where["nickname"] = "%".$nickname."%";
       }
       if(!empty($name)){
           $sql .=" AND a.First_Name LIKE :name";
           $where["name"]="%".$name."%";
       }
       if(!empty($lname)){
           $sql .=" AND a.Last_Name LIKE :lname";
           $where["lname"]="%".$lname."%";
       }
       if(!empty($type)){
           $sql .=" AND a.SysAngelType = :type";
           $where["type"]=$type;
       }
       if(!empty($status)){
           $sql .=" AND a.Angel_Status = :status";
           $where["status"]=$status;
       }
       $sql .= " Order by a.Angel_ID";
       $sql .= " LIMIT ".$start.",".$length;
        $userlist = DB::select($sql,$where);
        return $userlist;
    }
    public function getPrettyWorkingList($empcode,$nickname,$start=0,$length=10){
        $where = array();
        $sql = "SELECT a.*,b.Angel_Type_Code
    FROM mas_angel a
            inner join mas_angel_type b on a.SysAngelType=b.SysAngelType
        WHERE a.Delete_Flag = 'N' AND a.Angel_Status IN ('NW','WK')";
       if(!empty($empcode)){
            $sql .=" AND a.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
           $sql .=" AND a.Nick_Name LIKE :nickname";
           $where["nickname"] = "%".$nickname."%";
       }
       $sql .= " LIMIT ".$start.",".$length;
        $userlist = DB::select($sql,$where);
        return $userlist;
    }
    public function getPrettyWorkingCount($empcode,$nickname){
        $where = array();
        $sql = "SELECT Count(*) as cc
       FROM mas_angel a
        WHERE a.Delete_Flag = 'N' AND a.Angel_Status IN ('NW','WK')";
       if(!empty($empcode)){
            $sql .=" AND a.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
           $sql .=" AND a.Nick_Name LIKE :nickname";
           $where["nickname"] = "%".$nickname."%";
       }
      
       $count = collect(\DB::select($sql,$where))->first();
       return $count->cc;
    }
    public function getPrettyCount($empcode,$nickname,$name,$lname,$type,$status){
        $where = array();
        $sql = "SELECT Count(*) as cc
       FROM mas_angel a
        WHERE a.Delete_Flag = 'N'";
       if(!empty($empcode)){
            $sql .=" AND a.Angel_ID LIKE :empcode";
            $where["empcode"]= "%".$empcode."%";
       }
       if(!empty($nickname)){
           $sql .=" AND a.Nick_Name LIKE :nickname";
           $where["nickname"] = "%".$nickname."%";
       }
       if(!empty($name)){
           $sql .=" AND a.First_Name LIKE :name";
           $where["name"]="%".$name."%";
       }
       if(!empty($lname)){
           $sql .=" AND a.Last_Name LIKE :lname";
           $where["lname"]="%".$lname."%";
       }
       if(!empty($type)){
           $sql .=" AND a.SysAngelType = :type";
           $where["type"]=$type;
       }
       if(!empty($status)){
           $sql .=" AND a.Angel_Status = :status";
           $where["status"]=$status;
       }
       $count = collect(\DB::select($sql,$where))->first();
       return $count->cc;
    }
     
    public function getPretty($id){
       $sql ="SELECT  a.SysAngel_ID,a.SysAngelType,b.Angel_Type_Code,a.Part_Time_Flag,a.Angel_ID,a.First_Name,a.Last_Name,a.Nick_Name,a.Citizen_ID,
            a.Tel_No,a.Angel_Status,a.Debt1,a.Debt2,a.Debt3,a.Debt4,a.Debt5,
            b.Angel_Type_Code,b.Angel_Fee,b.Angel_Wage,b.round_time
                FROM mas_angel a 
                        inner join mas_angel_type b on a.SysAngelType=b.SysAngelType
                WHERE   a.SysAngel_ID = ? ";

       $pretty = collect(\DB::select($sql,[$id]))->first();

       return $pretty;
   }
   public function addPrettyType($params = array()){
         $id = DB::table('mas_angel_type')->insertGetId($params);
        return $id;
    }
    public function checkDuplicatePrettyCode($code,$id_check = ""){
        $where = array();
        $where["code"] = $code;
        $sql ="SELECT Count(*) as cc FROM mas_angel WHERE Angel_ID = :code AND Delete_Flag = 'N'";
        if(!empty($id_check)){
            $sql .=" AND SysAngel_ID <> :id";
            $where["id"] = $id_check;
        }
        $chk = collect(\DB::select($sql,$where))->first();

        return ($chk->cc > 0) ? true : false;
    }
    public function checkDuplicatePrettyRFID($rfid,$id_check = ""){
        $where = array();
        $where["code"] = $rfid;
        $sql ="SELECT Count(*) as cc FROM mas_rfid_map rf 
            inner join mas_angel a on rf.SysObject_ID=a.SysAngel_ID AND a.Delete_Flag != 'Y' WHERE rf.SysRFID_ID = :code AND rf.Object_Type = 'AN'";
        if(!empty($id_check)){
            $sql .=" AND rf.SysObject_ID <> :id";
            $where["id"] = $id_check;
        }
        $chk = collect(\DB::select($sql,$where))->first();

        return ($chk->cc > 0) ? true : false;
    }
   public function addPretty($params = array()){
         $id = DB::table('mas_angel')->insertGetId($params);
        return $id;
    }
    public function editPrettyDeductDebt($field,$dvalue,$id){
        $sql ="UPDATE mas_angel
                SET 
                    ".$field." = ".$field." - ?
                WHERE SysAngel_ID = ? ";
        $upd = DB::update($sql,[$dvalue,$id]);
        return $upd;
    }
    public function editPretty($params = array()){
        $sql ="UPDATE mas_angel
                SET 
                    SysAngelType = :SysAngelType,
                    Angel_ID = :Angel_ID,
                    First_Name = :First_Name,
                    Last_Name =:Last_Name,
                    Nick_Name = :Nick_Name,
                    Citizen_ID = :Citizen_ID,
                    Tel_No = :Tel_No,
                    LastUpd_Dtm = :LastUpd_Dtm,
                    LastUpd_User_ID = :LastUpd_User_ID,
                    Part_Time_Flag = :Part_Time_Flag,
                    Debt1 = :Debt1,
                    Debt2 = :Debt2,
                    Debt3 = :Debt3,
                    Debt4 = :Debt4,
                    Debt5 = :Debt5,
                    Angel_Status = :Angel_Status
                WHERE SysAngel_ID = :id
        ";
        $upd = DB::update($sql,$params);
        return $upd;
    }
    public function editPrettyType($params = array()){
        $sql ="UPDATE mas_angel_type
                SET 
                    Angel_Type_Code = :Angel_Type_Code,
                    Angel_Type_Desc = :Angel_Type_Desc,
                    Angel_Fee = :Angel_Fee,
                    Angel_Wage =:Angel_Wage,
                    LastUpd_Dtm = :LastUpd_Dtm,
                    LastUpd_User_ID = :LastUpd_User_ID,
                    Credit_Comm = :Credit_Comm,
                    round_time = :round_time
                WHERE SysAngelType = :id
        ";
        $upd = DB::update($sql,$params);
        return $upd;
    }
    public function addRfidCard($params = array()){
         $id = DB::table('mas_rfid_map')->insert($params);
        return $id;
    }
    public function getPrettyByCard($card){
        $sql = "SELECT a.SysRFID_ID,b.SysAngel_ID,b.Angel_ID,b.Nick_Name,b.Angel_Status
                FROM mas_rfid_map a
                            inner join mas_angel b on a.SysObject_ID=b.SysAngel_ID AND a.Object_Type = 'AN' AND b.Delete_Flag = 'N'
                WHERE a.SysRFID_ID = ?
                ";
         $rfid = collect(DB::select($sql,[$card]))->first();
        return $rfid;
    }
    public function stampTimeWork($date,$card,$type){
        $sqlChk = "SELECT Count(*) as cc FROM mas_rfid_time WHERE Txn_Date = ? AND SysRFID_ID = ?";
        $qChk = collect(\DB::select($sqlChk,[$date,$card]))->first();
        $result = 0;
        if($qChk->cc > 0){ //update
            $sqlUpd = "UPDATE mas_rfid_time 
                SET 
                    ";
            if($type=="IN"){
                    $sqlUpd .= " Time_ComeIn = SYSDATE(),Time_ComeOut = NULL";
            }else{
                $sqlUpd .= " Time_ComeOut = SYSDATE()";
            }
            $sqlUpd .=" WHERE Txn_Date = ? AND SysRFID_ID = ?";
            $result = DB::update($sqlUpd,[$date,$card]);
        }else{ //insert
            $insObj = array(
                "Txn_Date"=>$date,
                "SysRFID_ID"=>$card
            );
            if($type=="IN"){
                $insObj["Time_ComeIn"] = date("Y-m-d H:i:s");
            }else{
                $insObj["Time_ComeOut"] = date("Y-m-d H:i:s");
            }
            $result = DB::table("mas_rfid_time")->insert($insObj);
        }
        return $result;
    }
    public function getRfidCard($oid,$type){
         $rfid = collect(DB::select("SELECT * FROM mas_rfid_map WHERE SysObject_ID = ? AND Object_Type = ?",[$oid,$type]))->first();
        return $rfid;
    }
    public function delRfidCard($oid,$type){
         $rfid = DB::delete("DELETE FROM mas_rfid_map WHERE SysObject_ID = ? AND Object_Type = ?",[$oid,$type]);
        return $rfid;
    }
    public function delPretty($id){
        $params = array();
        $params["id"] = $id;
        $sql="update mas_angel set Delete_Flag = 'Y',Work_To_Date=SYSDATE() Where SysAngel_ID = :id";
        $res = DB::update($sql, $params);
        return $res;
    }
    public function delPrettyType($id){
        $params = array();
        $params["id"] = $id;
        $sql="update mas_angel_type set Delete_Flag = 'Y' Where SysAngelType = :id";
        $res = DB::update($sql, $params);
        return $res;
    }
   public function getPrettyType($id = ""){
       $params=array();
       $sql ="SELECT * FROM mas_angel_type WHERE Delete_Flag='N'";
       if(!empty($id)){
           $sql .= " AND SysAngelType = ?";
           $params[] = $id;
       }
       $list = DB::select($sql,$params);

       return $list;
   }
   public function getPrettyTypeByID($id){
       $sql ="SELECT * FROM mas_angel_type WHERE SysAngelType = ?";
       $list = collect(\DB::select($sql,[$id]))->first();

       return $list;
   }
   public function getPrettyStatus(){
       $sql ="SELECT Ref_Code,Ref_Desc FROM mas_reference WHERE Ref_Type = 'ANGEL_STATUS'";
       $list = DB::select($sql);
       return $list;
   }
   public function updatePrettyStatus($id,$status){
       $sql ="UPDATE mas_angel
        SET
            Angel_Status = ?
        WHERE SysAngel_ID = ?";
        $res = DB::update($sql, [$status,$id]);
        return $res;
   }
   public function getReceptionWork($start,$end,$rc_id){
       $where = array();
       $sql = "SELECT al.Txn_Date,al.Txn_No,an.Angel_ID,an.Nick_Name,r.Room_No,al.Round

FROM tnd_massage_angel_list al
			INNER JOIN mas_reception rc on al.SysReception_ID=rc.SysReception_ID
			INNER JOIN mas_angel an on al.SysAngel_ID=an.SysAngel_ID
			INNER JOIN mas_room r on al.SysRoom_ID=r.SysRoom_ID

WHERE al.Paid_Status = 'Y' ";
        if(!empty($start)){
            $sql .= "   AND al.Txn_Date >= ?";
            $where[] = $start;
        }
        if(!empty($end)){
            $sql .= "   AND al.Txn_Date <= ?";
            $where[] = $end;
        }
        if(!empty($start)){
            $sql .= "   AND al.SysReception_ID = ?";
            $where[] = $rc_id;
        }
        $list = DB::select($sql,$where);

        return $list;
   }
   
}
