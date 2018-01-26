<?php

/**
 * Created by PhpStorm.
 * User: lmh
 * Date: 2017/3/24
 * Time: 9:41
 */
class UserModel
{
    static public  function  GetAllUsers($params)
    {
         $db=GetCfgDb();
         $ret=$db->select("user_info_tbl","*");
         return $ret;
    }

    static public function ValidateUser($info)
    {
         $db=GetCfgDb();
         $user=$info["username"];
         $pwd=$info["password"];
         $ret=array("pass"=>false,"level"=>0,"msg"=>"");

         $data=$db->select("user_info_tbl",["password","user_level","user_desc"],["user_name"=>$user]);
         if(count($data)<=0)
         {
              $ret["msg"]="用户名不存在";
         }
         else
         {
             $ret["level"]=$data[0]["user_level"];
             if($pwd==$data[0]["password"])
              {
                   $ret["pass"]=true;
                   $ret["desc"]=$data[0]["user_desc"];
              }
              else
              {
                   $ret["msg"]="密码错误";
              }
         }
         return $ret;
    }

    static public function AddOrModifyUser($info)
    {
        $db=GetCfgDb();
        $user=$info["username"];
        $pwd=$info["password"];
        $desc=$info["desc"];
        $ret=0;
        $data=$db->select("user_info_tbl","*",["user_name"=>$user]);
        if(count($data)<=0)  //insert
        {
            $ret=$db->insert("user_info_tbl",["user_name"=>$user,"password"=>$pwd,"user_level"=>0,"user_desc"=>$desc]);
        }
        else  //update
        {
             $ret=$db->update("user_info_tbl",["password"=>$pwd,"user_desc"=>$desc],["user_name"=>$user]);
        }
        return ["result"=>$ret];
    }

    static public function RemoveUser($info)
    {
        $db=GetCfgDb();
        $user=$info["username"];
        $ret=$db->delete("user_info_tbl",["user_name"=>$user]);
        return ["result"=>$ret];
    }
}