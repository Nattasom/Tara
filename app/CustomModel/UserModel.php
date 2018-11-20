<?php

namespace App\CustomModel;

use Illuminate\Support\Facades\DB;

class UserModel
{
    public function login($username,$password){
        $response = array();
        $encPassword = md5($password);
        $user = DB::select("select * from mas_user where Login_Name = ? AND Password = ? AND User_Status = 'AC' AND Delete_Flag = 'N'", [$username,$encPassword]);
        if(count($user) > 0){
            $response["status"] = "01";
            $response["user"] = $user[0];
            $response["user"]->Menu = $this->getMenuByUser($user[0]->SysUser_ID);
            
        }
        else {
            $response["status"] = "02";
        }
        return $response;
    }
    public function getUserList($username,$flname,$start=0,$length=10){
        $where = array();
        $sql = "select * from mas_user where Delete_Flag = 'N'";
       if(!empty($username)){
            $sql .=" AND Login_Name LIKE :username";
            $where["username"]= "%".$username."%";
       }
       if(!empty($flname)){
           $sql .=" AND User_Fullname LIKE :fullname";
           $where["fullname"] = "%".$flname."%";
       }
       $sql .= " LIMIT ".$start.",".$length;
        $userlist = DB::select($sql,$where);
        return $userlist;
    }
    public function getCount($username,$flname){
        $where = array();
        $sql = "select count(*) as cc from mas_user where Delete_Flag = 'N'";
       if(!empty($username)){
            $sql .=" AND Login_Name LIKE :username";
            $where["username"]= "%".$username."%";
       }
       if(!empty($flname)){
           $sql .=" AND User_Fullname LIKE :fullname";
           $where["fullname"] = "%".$flname."%";
       }
       $count = collect(\DB::select($sql,$where))->first();
       return $count->cc;
    }
    public function getRoleCount($rolecode,$rolename,$roledesc){
        $where = array();
        $sql = "select count(*) as cc from mas_role where Delete_Flag = 'N'";
       if(!empty($rolecode)){
            $sql .=" AND Role_Code LIKE :rolecode";
            $where["rolecode"]= "%".$rolecode."%";
       }
       if(!empty($rolename)){
           $sql .=" AND Role_Name LIKE :rolename";
           $where["rolename"] = "%".$rolename."%";
       }
       if(!empty($roledesc)){
           $sql .=" AND Role_Desc LIKE :roledesc";
           $where["roledesc"] = "%".$roledesc."%";
       }
       $count = collect(\DB::select($sql,$where))->first();
       return $count->cc;
    }
    public function getRoleList($rolecode,$rolename,$roledesc,$start=0,$length=10){
        $where = array();
        $sql = "select * from mas_role where Delete_Flag = 'N'";
       if(!empty($rolecode)){
            $sql .=" AND Role_Code LIKE :rolecode";
            $where["rolecode"]= "%".$rolecode."%";
       }
       if(!empty($rolename)){
           $sql .=" AND Role_Name LIKE :rolename";
           $where["rolename"] = "%".$rolename."%";
       }
       if(!empty($roledesc)){
           $sql .=" AND Role_Desc LIKE :roledesc";
           $where["roledesc"] = "%".$roledesc."%";
       }
       $sql .= " LIMIT ".$start.",".$length;
        $userlist = DB::select($sql,$where);
        return $userlist;
    }
    public function getPageGroup(){
        $sql = "SELECT * FROM m_page_group Order by seq";

        $list = DB::select($sql);

        return $list;
    }
    public function getPageWithAction(){
        $sql ="SELECT * FROM m_page Order by page_group_id,page_seq";
        $list = DB::select($sql);
        $pagelist = array();
        foreach($list as $key=>$value){
            $pageAction = array();
            $sqlAct = "SELECT * FROM m_page_action WHERE page_id = ?";
            $listAct = DB::select($sqlAct,[$value->page_id]);
            $tmp = new \stdClass();
            $tmp = $value;
            $tmp->action = $listAct;
            $pagelist[$value->page_group_id][] = $tmp;
        }
        return $pagelist;
    }
    public function addRolePermission($strsave,$id){
        DB::delete("DELETE FROM t_role_page WHERE role_id = ?",[$id]);
        $arrSave = explode(',',$strsave);
        $res = 0;
        foreach($arrSave as $key=>$value){
            $arrVal = explode(':',$value);
            $res += DB::insert('insert into t_role_page (role_id, page_id,action_code) values (?,?,?)', [$id,$arrVal[0],$arrVal[1]]);
        }

        return $res;
    }
    public function checkPermission($id,$page,$action){
        $sql = "SELECT Count(*) as cc FROM t_role_page WHERE role_id = ? AND action_code = ? AND page_id = ?";
        $res = collect(\DB::select($sql,[$id,$action,$page]))->first();
        if($res->cc > 0){
            return true;
        }else{
            return false;
        }
    }
    public function getRole($id){
        $sql="SELECT * FROM mas_role WHERE SysRole_ID = ?";
        $role = collect(\DB::select($sql,[$id]))->first();
        return $role;
    }
    public function getUser($id){
        $sql="SELECT * FROM mas_user WHERE SysUser_ID = ?";
        $user = collect(\DB::select($sql,[$id]))->first();
        return $user;
    }
    public function getUserRoleArray($id){
        $sql="SELECT * FROM mas_map_user_role WHERE SysUser_ID = ?";
        $user = DB::select($sql,[$id]);
        $array = array();
        foreach($user as $key=>$value){
            $array[] = $value->SysRole_ID;
        }
        return $array;
    }
    public function addUser($params = array(),$rolesave=array()){
        //check duplicate room
        $sql = "select Count(*) as cc FROM mas_user WHERE Login_Name=? AND Delete_Flag='N'";
        $chk = collect(\DB::select($sql,[$params["Login_Name"]]))->first();
        if($chk->cc > 0){//dup
            return 0;
        }
        $id = DB::table('mas_user')->insertGetId($params);
        foreach($rolesave as $key=>$value){
            DB::insert('insert into mas_map_user_role (SysUser_ID, SysRole_ID) values (?, ?)', [$id,$value]);
        }
        
        return $id;
    }
    public function editUser($params = array(),$rolesave=array()){
       $sqlUpdate = "UPDATE mas_user
                    SET
                        Login_Name = :Login_Name,
                        User_Fullname = :User_Fullname,
                        User_Citizen_ID = :User_Citizen_ID,
                        User_Address = :User_Address,
                        User_Tel_No = :User_Tel_No,
                        RF_Card_No = :RF_Card_No,
                        LastUpd_User_Dtm = :LastUpd_User_Dtm,
                        LastUpd_User_ID = :LastUpd_User_ID
                    WHERE SysUser_ID = :id
                    ";
        $id = DB::update($sqlUpdate,$params);

        //Clear ex-role
        DB::delete("DELETE FROM mas_map_user_role WHERE SysUser_ID = ?",[$params["id"]]);
        foreach($rolesave as $key=>$value){
            DB::insert('insert into mas_map_user_role (SysUser_ID, SysRole_ID) values (?, ?)', [$params["id"],$value]);
        }
        
        return $id;
    }
    public function editPasswordUser($password,$id){
        $sqlUpdate = "UPDATE mas_user
                    SET
                        Password = ?
                    WHERE SysUser_ID = ?
                    ";
        $id = DB::update($sqlUpdate,[$password,$id]);
        return $id;
    }
    public function addRole($params = array()){
        $id = DB::table('mas_role')->insertGetId($params);
        return $id;
    }
    public function editRole($params = array()){
        $sql = "UPDATE mas_role
                    SET
                        Role_Code = :Role_Code,
                        Role_Name = :Role_Name,
                        Role_Desc = :Role_Desc,
                        LastUpd_Dtm = :LastUpd_Dtm,
                        LastUpd_User_ID = :LastUpd_User_ID
                    WHERE   SysRole_ID = :id
        ";

        $res = DB::update($sql,$params);
        return $res;
    }
    public function delRole($id){
        $sql = "UPDATE mas_role
                    SET
                        Delete_Flag = 'Y'
                    WHERE   SysRole_ID = ?
        ";

        $res = DB::update($sql,[$id]);
        return $res;
    }
    public function delUser($id){
        $sql = "UPDATE mas_user
                    SET
                        Delete_Flag = 'Y'
                    WHERE   SysUser_ID = ?
        ";

        $res = DB::update($sql,[$id]);
        return $res;
    }
    function getMenuByUser($member_id){
        $sql = "SELECT DISTINCT  p.page_group_id,pg.page_group_element_id,pg.page_group_name,pg.page_group_hassub,pg.page_group_icon,pg.page_group_url

FROM  m_page p
			inner join t_role_page rp on p.page_id=rp.page_id
			inner join m_page_group pg on p.page_group_id=pg.page_group_id


WHERE  rp.role_id IN (SELECT SysRole_ID FROM mas_map_user_role WHERE SysUser_ID = ?) AND rp.action_code = 'VIEW'

ORDER BY pg.seq";
        $sqlPage = "SELECT DISTINCT  p.page_group_id,pg.page_group_element_id,pg.page_group_name,pg.page_group_hassub,pg.page_group_icon,pg.page_group_url,p.page_id,p.page_name,p.page_url

FROM  m_page p
			inner join t_role_page rp on p.page_id=rp.page_id
			inner join m_page_group pg on p.page_group_id=pg.page_group_id


WHERE  rp.page_id IN (SELECT page_id FROM m_page WHERE p.page_group_id = ?) and rp.role_id IN (SELECT SysRole_ID FROM mas_map_user_role WHERE SysUser_ID = ?)  AND rp.action_code = 'VIEW'

ORDER BY p.page_seq";
        $list = DB::select($sql,[$member_id]);
        $menu = array();
        foreach($list as $key=>$value){
            $tmp = new \stdClass();
            $tmp = $value;
            $sublist = DB::select($sqlPage,[$tmp->page_group_id,$member_id]);
            $tmp->sub_menu = $sublist;
            $menu[] = $tmp;
        }

        return $menu;
    }
    function checkActionUser($member_id,$page,$action){
        $sql = "SELECT DISTINCT  p.page_group_id,pg.page_group_element_id,pg.page_group_name,pg.page_group_hassub,pg.page_group_icon,pg.page_group_url

FROM  m_page p
			inner join t_role_page rp on p.page_id=rp.page_id
			inner join m_page_group pg on p.page_group_id=pg.page_group_id
WHERE  rp.role_id IN (SELECT SysRole_ID FROM mas_map_user_role WHERE SysUser_ID = ?) AND p.page_id = ? AND rp.action_code = ?

ORDER BY pg.seq";
        $list = DB::select($sql,[$member_id,$page,$action]);
        if(count($list) > 0){
            return true;
        }else{
            return false;
        }
    }
}
