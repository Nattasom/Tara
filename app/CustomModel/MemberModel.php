<?php

namespace App\CustomModel;

use Illuminate\Support\Facades\DB;
use App\CustomModel\UtilModel;

class MemberModel
{
    private $util;
    function __construct(){
        $this->util = new UtilModel();
    }

   public function getMemberList($member_no,$member_name,$member_lastname,$recept_id,$status,$start=0,$length=10,$month="",$year=""){
    $where = array();
        $sql = "SELECT a.*,r.First_Name as Recept_Name,r.Last_Name as Recept_Lastname
    FROM mas_member a
            inner join mas_reception r on a.SysReception_ID=r.SysReception_ID
        WHERE a.Delete_Flag = 'N'";
       if(!empty($member_no)){
            $sql .=" AND a.Member_ID LIKE :member_no";
            $where["member_no"]= "%".$member_no."%";
       }
       if(!empty($member_name)){
           $sql .=" AND a.First_Name LIKE :member_name";
           $where["member_name"] = "%".$member_name."%";
       }
       if(!empty($member_lastname)){
           $sql .=" AND a.Last_Name LIKE :member_lastname";
           $where["member_lastname"] = "%".$member_lastname."%";
       }
       if(!empty($recept_id)){
           $sql .=" AND a.SysReception_ID = :recept_id";
           $where["recept_id"]=$recept_id;
       }
       if(!empty($status)){
           $sql .=" AND a.Member_Status = :status";
           $where["status"]=$status;
       }
       if(!empty($month)){
           $sql .=" AND MONTH(a.Expired_Date) = :month";
           $where["month"]=$month;
       }
       if(!empty($year)){
           $sql .=" AND YEAR(a.Expired_Date) = :year";
           $where["year"]=$year;
       }
       //$sql .=" Order By a.Room_No,a.Room_Order ";
       $sql .= " LIMIT ".$start.",".$length;
        $memberlist = DB::select($sql,$where);
        return $memberlist;
   }
   public function getCount($member_no,$member_name,$member_lastname,$recept_id,$status,$month="",$year=""){
        $where = array();
            $sql = "SELECT Count(*) as cc
        FROM mas_member a
                inner join mas_reception r on a.SysReception_ID=r.SysReception_ID
            WHERE a.Delete_Flag = 'N'";
        if(!empty($member_no)){
                $sql .=" AND a.Member_ID LIKE :member_no";
                $where["member_no"]= "%".$member_no."%";
        }
        if(!empty($member_name)){
            $sql .=" AND a.First_Name LIKE :member_name";
            $where["member_name"] = "%".$member_name."%";
        }
        if(!empty($member_lastname)){
            $sql .=" AND a.Last_Name LIKE :member_lastname";
            $where["member_lastname"] = "%".$member_lastname."%";
        }
        if(!empty($recept_id)){
            $sql .=" AND a.SysReception_ID = :recept_id";
            $where["recept_id"]=$recept_id;
        }
        if(!empty($status)){
           $sql .=" AND a.Member_Status = :status";
           $where["status"]=$status;
       }
       if(!empty($month)){
           $sql .=" AND MONTH(a.Expired_Date) = :month";
           $where["month"]=$month;
       }
       if(!empty($year)){
           $sql .=" AND YEAR(a.Expired_Date) = :year";
           $where["year"]=$year;
       }
        $count = collect(\DB::select($sql,$where))->first();
        return $count->cc;
    }
    public function getMemberPayList($member_no,$member_name,$member_lastname,$date,$start=0,$length=10){
    $where = array();
        $sql = "SELECT a.*,m.Member_ID,m.First_Name
from tnh_member_application a
		inner join mas_member m on a.SysMember_ID = m.SysMember_ID
where a.Paid_Date IS NOT NULL";
       if(!empty($member_no)){
            $sql .=" AND m.Member_ID LIKE :member_no";
            $where["member_no"]= "%".$member_no."%";
       }
       if(!empty($member_name)){
           $sql .=" AND m.First_Name LIKE :member_name";
           $where["member_name"] = "%".$member_name."%";
       }
       if(!empty($member_lastname)){
           $sql .=" AND m.Last_Name LIKE :member_lastname";
           $where["member_lastname"] = "%".$member_lastname."%";
       }
       if(!empty($date)){
           $sql .=" AND a.Paid_Date = :date";
           $where["date"] = $date;
       }
       //$sql .=" Order By a.Room_No,a.Room_Order ";
       $sql .= " LIMIT ".$start.",".$length;
        $memberlist = DB::select($sql,$where);
        return $memberlist;
   }
   public function getMemberPayCount($member_no,$member_name,$member_lastname,$date){
        $where = array();
            $sql = "SELECT Count(*) as cc
        from tnh_member_application a
		inner join mas_member m on a.SysMember_ID = m.SysMember_ID
where a.Paid_Date IS NOT NULL";
        if(!empty($member_no)){
            $sql .=" AND m.Member_ID LIKE :member_no";
            $where["member_no"]= "%".$member_no."%";
       }
       if(!empty($member_name)){
           $sql .=" AND m.First_Name LIKE :member_name";
           $where["member_name"] = "%".$member_name."%";
       }
       if(!empty($member_lastname)){
           $sql .=" AND m.Last_Name LIKE :member_lastname";
           $where["member_lastname"] = "%".$member_lastname."%";
       }
       if(!empty($date)){
           $sql .=" AND a.Paid_Date = :date";
           $where["date"] = $date;
       }
        $count = collect(\DB::select($sql,$where))->first();
        return $count->cc;
    }
    public function getMemberDebtList($member_no,$member_name,$member_lastname,$date,$start=0,$length=10){
    $where = array();
        $sql = "SELECT a.*,m.Member_ID,m.First_Name
from tnh_member_application a
		inner join mas_member m on a.SysMember_ID = m.SysMember_ID
where a.Paid_Date IS NULL";
       if(!empty($member_no)){
            $sql .=" AND m.Member_ID LIKE :member_no";
            $where["member_no"]= "%".$member_no."%";
       }
       if(!empty($member_name)){
           $sql .=" AND m.First_Name LIKE :member_name";
           $where["member_name"] = "%".$member_name."%";
       }
       if(!empty($member_lastname)){
           $sql .=" AND m.Last_Name LIKE :member_lastname";
           $where["member_lastname"] = "%".$member_lastname."%";
       }
       if(!empty($date)){
           $sql .=" AND a.Txn_Date = :date";
           $where["date"] = $date;
       }
       //$sql .=" Order By a.Room_No,a.Room_Order ";
       $sql .= " LIMIT ".$start.",".$length;
        $memberlist = DB::select($sql,$where);
        return $memberlist;
   }
   public function getMemberDebtCount($member_no,$member_name,$member_lastname,$date){
        $where = array();
            $sql = "SELECT Count(*) as cc
        from tnh_member_application a
		inner join mas_member m on a.SysMember_ID = m.SysMember_ID
where a.Paid_Date IS NOT NULL";
        if(!empty($member_no)){
            $sql .=" AND m.Member_ID LIKE :member_no";
            $where["member_no"]= "%".$member_no."%";
       }
       if(!empty($member_name)){
           $sql .=" AND m.First_Name LIKE :member_name";
           $where["member_name"] = "%".$member_name."%";
       }
       if(!empty($member_lastname)){
           $sql .=" AND m.Last_Name LIKE :member_lastname";
           $where["member_lastname"] = "%".$member_lastname."%";
       }
       if(!empty($date)){
           $sql .=" AND a.Txn_Date = :date";
           $where["date"] = $date;
       }
        $count = collect(\DB::select($sql,$where))->first();
        return $count->cc;
    }
    public function getMemberType(){
        $sql ="SELECT * FROM mas_member_type ";
        $list = DB::select($sql);

        return $list;
    }
    public function getMemberStatus(){
        $sql ="SELECT Ref_Code,Ref_Desc FROM mas_reference WHERE Ref_Type = 'MEMBER_STATUS' ";
        $list = DB::select($sql);

        return $list;
    }
    public function getMember($id){
        $sql="SELECT m.*,CONCAT(r.First_Name,' ',IFNULL(r.Last_Name,'')) as Recept_Name,r.Nick_Name,r.Reception_ID FROM mas_member m inner join mas_reception r on m.SysReception_ID = r.SysReception_ID WHERE m.SysMember_ID = ?";
        $member = collect(\DB::select($sql,[$id]))->first();

        return $member;
    }
    public function addMember($params=array()){
        $id = DB::table('mas_member')->insertGetId($params);
        return $id;
    }
    public function editMemberData($params=array()){
        $sql = "UPDATE mas_member
                    SET
                        Member_ID = :Member_ID,
                        First_Name = :First_Name,
                        Last_Name = :Last_Name,
                        Address = :Address,
                        Tel_No = :Tel_No,
                        SysReception_ID = :SysReception_ID,
                        Member_From_Date = :Member_From_Date,
                        Expired_Date = :Expired_Date,
                        Member_Status = :Member_Status,
                        LastUpd_Dtm = :LastUpd_Dtm,
                        LastUpd_User_ID = :LastUpd_User_ID
                    WHERE SysMember_ID = :id
        ";
        $id = DB::update($sql, $params);
        return $id;
    }
    public function cutMemberSuite($id,$amt){
        $sql = "UPDATE mas_member
                    SET
                        Suite = Suite - ?
                    WHERE SysMember_ID = ?
        ";
        $id = DB::update($sql, [$amt,$id]);
        return $id;
    }
    public function cutMemberSuite2($id,$amt){
        $sql = "UPDATE mas_member
                    SET
                        Suite2 = Suite2 - ?
                    WHERE SysMember_ID = ?
        ";
        $id = DB::update($sql, [$amt,$id]);
        return $id;
    }
    public function cutCreditAmount($id,$amt){
        $sql = "UPDATE mas_member
                    SET
                        Credit_Amt = Credit_Amt - ?
                    WHERE SysMember_ID = ?
        ";
        $id = DB::update($sql, [$amt,$id]);
        return $id;
    }
    public function delMember($id){
        $params = array();
        $params["id"] = $id;
        $sql="update mas_member set Delete_Flag = 'Y' Where SysMember_ID = :id";
        $res = DB::update($sql, $params);
        return $res;
    }
    public function addMemberApplication($params = array()){
        $id = DB::table('tnh_member_application')->insertGetId($params);
        return $id;
    }
    public function getMemberSellList($start,$end){
        $params = array();
        $sql ="SELECT m.Member_From_Date,m.Member_ID,m.First_Name,rc.Nick_Name as RC_Nickname,ma.Add_Credit,ma.Cash_Amt,ma.Credit_Amt,ma.Unpaid_Amt,u.User_Fullname
            FROM
                    mas_member m 
                        inner join mas_reception rc on m.SysReception_ID=rc.SysReception_ID
                        inner join tnh_member_application ma on m.SysMember_ID=ma.SysMember_ID
                        inner join mas_user u on ma.CreateBy_USER_ID=u.SysUser_ID
            WHERE 1=1
        ";
        if(!empty($start)){
            $sql .= "    AND ma.Paid_Date >= :start";
            $params["start"] = $this->util->dateThaiToSystemFormat($start);
        }
        if(!empty($end)){
            $sql .= "    AND ma.Paid_Date <= :end";
            $params["end"] = $this->util->dateThaiToSystemFormat($end);
        }
        $list = DB::select($sql,$params);

        return $list;
    }
    public function getMemberUseList($start,$end,$building=""){
        $params = array();
        $sql ="SELECT  m.Member_ID,SUM(mp.FNB_Paid_Amt) as FNB_Amt,SUM(mp.Angel_Paid_Amt) as Angel_Amt,SUM(mp.Member_Suite_Used) as Suite_Used,SUM(mp.FNB_Paid_Amt+mp.Angel_Paid_Amt) as Sum_Total,m.Credit_Amt

FROM	tnd_member_payment mp 
			inner join mas_member m on mp.SysMember_ID=m.SysMember_ID
            left join mas_room ro on (SELECT SysRoom_ID FROM tnd_massage_angel_list an where mp.Txn_Date=an.Txn_Date AND mp.Txn_No=an.Txn_No LIMIT 1)=ro.SysRoom_ID
where 1=1";
    if(!empty($start)){
            $sql .= "    AND mp.Txn_Date >= :start";
            $params["start"] = $this->util->dateThaiToSystemFormat($start);
        }
        if(!empty($end)){
            $sql .= "    AND mp.Txn_Date <= :end";
            $params["end"] = $this->util->dateThaiToSystemFormat($end);
        }
        if(!empty($building)){
            $sql.= " AND ro.building_id = :building";
            $params["building"] = $building;
        }
        $sql .=" Group By mp.SysMember_ID,m.Member_ID,m.Credit_Amt";
        
        $list = DB::select($sql,$params);

        return $list;
    }
    public function getMemberApplication($id){
        $sql = "SELECT * FROM tnh_member_application WHERE SysMember_Application_ID = ?";

        $res = collect(\DB::select($sql,[$id]))->first();

        return $res;
    }
    public function editMemberApplication($params=array()){
        $sql ="UPDATE tnh_member_application
            SET
                Cash_Amt = :Cash_Amt,
                Credit_Amt = :Credit_Amt,
                Paid_Date = :Paid_Date,
                LastUpd_User_ID = :uid
            WHERE SysMember_Application_ID = :id
        ";

        $res = DB::update($sql,$params);

        return $res;
    }
    public function topupMember($params=array()){
        $sql = "UPDATE mas_member
                    SET
                        Credit_Amt = Credit_Amt + :Credit_Amt,
                        Whisky = Whisky + :Whisky,
                        Whisky2 = Whisky2 + :Whisky2,
                        Suite = Suite + :Suite,
                        Suite2 = Suite2 + :Suite2,
                        Suite3 = Suite3 + :Suite3,
                        Expired_Date = DATE_ADD(SYSDATE(), INTERVAL 365 DAY)
                    WHERE SysMember_ID = :id
        ";
        $id = DB::update($sql, $params);
        return $id;
    }
    public function deductMember($params=array()){
        $sql = "UPDATE mas_member
                    SET
                        Whisky = Whisky - :Whisky,
                        Whisky2 = Whisky2 - :Whisky2,
                        Credit_Amt = Credit_Amt - :Credit_Amt,
                        Suite = Suite - :Suite,
                        Suite2 = Suite2 - :Suite2,
                        Suite3 = Suite3 - :Suite3
                    WHERE SysMember_ID = :id
        ";
        $id = DB::update($sql, $params);
        return $id;
    }
    public function memberSync($table)
    {
        $sql = "SELECT * FROM ".$table."";
        $list = DB::select($sql);
        foreach($list as $key=>$value){
            $date = null;
            $expired_date = null;
            $suite = 0;
            $suite1= 0; // 7/1 Amount
            $suite2 = 0; //Spa Amount
            $whisky = 0;
            $credit_amt = 0;
            if(!empty(trim($value->Date))){
                $arrFormat = explode('/',$value->Date);
                if(count($arrFormat) > 1){
                    if(count($arrFormat)==3){
                        $day= str_pad($arrFormat[0],2,'0',STR_PAD_LEFT);
                        $month = str_pad($arrFormat[1],2,'0',STR_PAD_LEFT);
                        $strYear = $arrFormat[2];
                        if(strlen($strYear)==2){
                            $strYear = "25".$strYear;
                        }
                        $year = intval($strYear)-543;
                        $date = $year.'-'.$month.'-'.$day;
                    }
                }
                else{
                    $arrMonth = array("Jun","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
                    $arrFormat1 = explode(' ',$value->Date);
                    if(count($arrFormat1)==3){
                        
                        $day= str_pad($arrFormat1[0],2,'0',STR_PAD_LEFT);
                        $month = str_pad(array_search($arrFormat1[1],$arrMonth)+1,2,'0',STR_PAD_LEFT);
                        $strYear = $arrFormat1[2];
                        if(strlen($strYear)==2){
                            $strYear = "25".$strYear;
                        }
                        
                        $year = intval($strYear)-543;
                        $date = $year.'-'.$month.'-'.$day;
                    }
                }
            }
            if(empty($value->{'Expired Date'})){
                $expired_date = date('Y-m-d', strtotime('+1 year', strtotime($date)));
            }
            if(!empty($value->{'Suite Amount'})){
                $suite = intval($value->{'Suite Amount'});
            }
            if(!empty($value->{'Suite 7/1 Amount'})){
                $suite1 = intval($value->{'Suite 7/1 Amount'});
            }
            if(!empty($value->{'Spa Amount'})){
                $suite2 = intval($value->{'Spa Amount'});
            }
            if(!empty($value->{'Whisky Amount'})){
                $whisky = intval($value->{'Whisky Amount'});
            }
            if(!empty($value->{'Credit Amt'})){
                $credit_amt = intval($value->{'Credit Amt'});
            }

            $params = array(
                "Member_ID"=>$value->{'Member Code'},
                "First_Name"=>$value->{'First Name'},
                "Address"=>"",
                "Tel_No"=>$value->{'Tel No'},
                "SysReception_ID"=>1,
                "Credit_Amt"=>$credit_amt,
                "Whisky"=>$whisky,
                "Suite"=>$suite,
                "Suite2"=>$suite1,
                "Suite3"=>$suite2,
                "Expired_Date"=>$expired_date,
                "Member_Status"=>'AV',
                "Member_From_Date"=>$date,
                "Delete_Flag"=>'N',
                "LastUpd_Dtm"=>date('Y-m-d H:i:s'),
                "LastUpd_User_ID"=>1,
                "Create_Dtm"=>date('Y-m-d'),
                "CreateBy_USER_ID"=>1
            );
            $this->addMember($params);
        }
    }
}
