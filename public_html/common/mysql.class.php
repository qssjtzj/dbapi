<?php

class mysql_db{

	protected $hostName = "";
	protected $hostport = "";
	protected $dbName = "";
	protected $dbUser = "";
	protected $dbPwd = ""; 
	protected $Link_ID = NULL;
	protected $times = 1;
	protected $dbQryResult = NULL;

	function __construct(){
		date_default_timezone_set("Asia/Taipei");
	}

	function setConfig($hostName="", $dbUser="", $dbPwd="",$dbName="", $port="", $times=1){
		if ($hostName!="") {
			$this->hostName = $hostName;
		}
		if ($dbUser!="") {
			$this->dbUser = $dbUser;
		}
		if ($dbPwd!="") {
			$this->dbPwd = $dbPwd;
		}
		if ($dbName!="") {
			$this->dbName = $dbName;
		}
		if ($port!="") {
			$this->hostport = $port;
		}
		if ($times!="") {
			$this->times = $times;
		}
		if ($this->Link_ID) {
			mysql_close($this->Link_ID);
		}
		$this->Link_ID = NULL;
	}

	function realConnect() {
		#$this->Link_ID = @mysql_connect($this->hostName, $this->dbUser, $this->dbPwd);
		#$this->Link_ID = @mysql_connect($this->hostName, $this->dbUser, $this->dbPwd);

		$this->Link_ID = new PDO("mysql:host=$this->hostName;dbname=$this->dbName","$this->dbUser","$this->dbPwd");	
		$this->Link_ID->select_db( $this->dbName );

/*
		$try = 1;
		while(!$this->Link_ID && $try<$this->times) {
			$this->writelog("connect $try error:".$this->error(), 'error');
			usleep(50000);
			$this->Link_ID = @mysql_connect($this->hostName, $this->dbUser, $this->dbPwd);
			$try++;
		}

		if(!$this->Link_ID) {
			$this->writelog("connect real error, host:".$this->hostName." info:".$this->error(), 'error');
			return false;
		}

		if (!@mysql_select_db($this->dbName, $this->Link_ID)) {
			$this->writelog("select databse error:".$this->error(), 'error');
			return false;
		}
*/
		return $this->Link_ID;
	}

	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	function disconnect()
	{
		@mysql_close($this->Link_ID);
	}

	function query($query){
		if (!$this->Link_ID) {
			if(!$this->realConnect()) {
				return false;
			}
		}
		$ip = "";
		if (isset($_SERVER["REMOTE_ADDR"])) {
			$ip = $_SERVER["REMOTE_ADDR"]; 
		} 
		
		$time_start = $this->microtime_float();
		$this->dbQryResult = @mysql_query($query, $this->Link_ID);
		if ($this->dbQryResult == false) {
			$errstr = mysql_error($this->Link_ID);
			if ($errstr) {
				$query = "query error! $errstr $query ";
				$this->writelog($query, 'error');
			}
			return false;
		}
		$time_end  = $this->microtime_float();
		$time_spend = $time_end-$time_start;
		$content    = sprintf("%04f-$ip-$this->hostName: %s", $time_spend, $query);
		$this->writelog($content);
		if ($time_spend > 0.5) {
			$this->writelog($content, 'slow');
		}
		return $this->dbQryResult;
	}

	function insert_id() {
		return ($id = @mysql_insert_id($this->Link_ID)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}
	function free_results()
	{
		return mysql_free_result($this->dbQryResult);
	}
	function fetch_row($result = "")
	{
		$dbResultLine = @mysql_fetch_row($result);
		return $dbResultLine;
	}

	function fetch_assoc($result = "")
	{
		$dbResultLine = @mysql_fetch_assoc($result);
		return $dbResultLine;
	}

	function fetch_object($result = "")
	{
		$dbResultLine = @mysql_fetch_object($result);
		return $dbResultLine;
	}
	function fetch_array($result = "", $array_type = MYSQL_BOTH)
	{
		$dbResultLine = @mysql_fetch_array($result, $array_type);
		return $dbResultLine;
	}
	function num_rows()
	{
		return @mysql_num_rows($this->dbQryResult);
	}
	function affected_rows()
	{
		return @mysql_affected_rows($this->Link_ID);
	}

	function error(){
		return @mysql_error($this->Link_ID);
	}

	function escape_string($string = ""){
		if ($this->Link_ID) {
			return mysql_real_escape_string($string, $this->Link_ID);
		} else {
			return mysql_escape_string($string);
		}
	}

	function writelog($content, $type = "")
	{
		$basedir = dirname(__FILE__);	
		$dir = "$basedir/../../../logs/mysql/";
		$filename = "";
		if ($type) {
			$filename = "$dir$type.".date("Y-m-d").".log";
		} else {
			$filename = "$dir".date("Y-m-d").".log";
		}
		if(!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}
		if(!$handle = fopen($filename, 'a+')) 
		{
			return false;
		}
		if (strlen($content > 300)) {
			$content = substr($context, 0, 300);
			$content .=  "...";
		}
		$content .=  "\n";
		if (fwrite($handle, $content) === FALSE) {
			return false;
		}
		fclose($handle);
		return true;
	}
}

?>
