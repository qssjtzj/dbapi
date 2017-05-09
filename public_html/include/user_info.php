<?php

/*__FILE__文件完整路径*/
define('USERINFO_LIB_PATH',dirname(__FILE__));
require_once(USERINFO_LIB_PATH.'/../common/dataclient.php');

class UserInfo extends DataClient {

	public function register_user($account, $params) 
	{
		$db = $this->getMysqlClient('iyoho_master');

		$params["passwd"] = $this->make_client_passwd($params["passwd"]);


		$result = $db->insert("uinfo", $params);
		$name = $params["nick"];

		if($result){
			$sql = "insert into uinfo_addr set uid='$result', name='$name'";
			$db->doSql($sql);


		}
		return $result;
	}


	public function get_uid_by_account($account)
	{
		$db = $this->getMysqlClient('iyoho_master');
		
		$sql = "select * from uinfo where account='$account'";
		$result= $db->doSql($sql);

		if(count($result) >0 ){
		    return $result[0]['id'];
		}
		return 0;
	}

	public function check_passwd($account, $passwd)
        {
                $db = $this->getMysqlClient('iyoho_master');

		$enpasswd = $this->make_client_passwd($passwd);
                $sql = "select * from uinfo where account='$account' and passwd='$enpasswd' ";
                $result= $db->doSql($sql);

		
                if(count($result) >0 ){
                    return $result;//[0]['id'];
                }
                return 0;
        }

	public function get_uinfo_addr($uid){
		 $db = $this->getMysqlClient('iyoho_master');

                $sql = "select * from uinfo_addr where uid='$uid'";
                $result= $db->doSql($sql);

                if(count($result) >0 ){
                    return $result[0];
                }
                return "";

	}

	public function update_uinfo_addr($uid, $params)
	{
		$db = $this->getMysqlClient('iyoho_master');
		
		$update_keys = array("name"=>true,
			"sex"=>true,
			"birthday"=>true,
			"age"=>true,
			"weight"=>true,
			"height"=>true,
			"email"=>true,
			"wx"=>true,
			"nationality"=>true,
			"zodiac"=>true,
			"constellation"=>true,
			"blood"=>true,
			"occupation"=>true,
			"faith"=>true,
			"marital_status"=>true,
			"address"=>true,
			"home_address"=>true,
			"smoking"=>true,
			"drinking"=>true,

			"education"=>true,
        	        "income"=>true,
                	"work_place"=>true,
	                "housing"=>true,
	                "caring"=>true,
        	        "cooking"=>true,
                	"housework"=>true,
	                "married_year"=>true,
        	        "live_parents"=>true,

        	        "max_consum"=>true,
	                "hobbies"=>true,
                	"favorite_place"=>true,
        	        "favorite_food"=>true,
	                "favorite_sports"=>true,
                	"favorite_music"=>true,
        	        "mate_age"=>true,
	                "mate_height"=>true,
                	"mate_education"=>true,

        	        "mate_marital"=>true,
	                "mate_work"=>true,
                	"mate_work_place"=>true,
        	        "mate_income"=>true,
	                "mate_smoking"=>true,
                	"mate_drinking"=>true,
        	        "mate_children"=>true,
	                "create_time"=>true,
                	"update_time"=>true,
		) ;


        	$sql_add = " update_time=now()";
	        foreach($params as $key=>$value) {
        		if(isset($update_keys[$key])) {
                		if(is_integer($value)) {
	            	    	     $sql_add .= ",$key=$value";
        	        	} else if (is_string($value)) {
            		    	     #$value = $db->escape_string($value);
		            	     $sql_add .= ",$key='$value'";
	        	        }
        	    	} else {
                		return false;
	            	}
        	}

	    	#$sql = "update uinfo_addr set $sql_add where uid=$uid";
		#$result = $db->doSql($sql);


		$sql = "UPDATE `uinfo_addr` SET `sex`=1 WHERE `uid`=41";
    		$result = $db->update('uinfo_addr', $params, "uid=$uid");
	    	
		if ($result > 0 ) {
    			return true;
    		}
	    	return false;
	}


	public function make_client_passwd($userpass) {
		$client_salt    = $this->make_client_salt();
		$encrypt_passwd = sha1($userpass.$client_salt);
		return $encrypt_passwd;
	}

	private function make_client_salt()
	{
		return "-yoho-client-salt-!@#$%^";
	}
};

?>
