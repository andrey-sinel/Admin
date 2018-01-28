<?php
  class ConnectMySQL{
    function __construct(){
      $this->сonnect();
    }
    
    function сonnect(){
      $this->ip = $_SERVER['REMOTE_ADDR'];
      include($_SERVER['DOCUMENT_ROOT']."/config.php");
      if ($this->ip=='127.0.0.1'){
        $this->db_host = $local_dbhost;
        $this->db_user = $local_dbuser;
        $this->db_pass = $local_dbpass;
        $this->db_name = $local_base;
      }
      else{
        $this->db_host = $dbhost;
        $this->db_user = $dbuser;
        $this->db_pass = $dbpass;
        $this->db_name = $base;
      }
      $this->db_link = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
      $this->query($this->db_link, "set names `utf8`");
    }
    
    function getData($sql, $pnum=0){
      if (is_string($sql) && !empty($sql)){
        $sql = str_replace(array(';','"'),'',$sql);
        $this->query_res = mysqli_query($this->db_link, $sql);
        if (!$this->query_res){
          return mysqli_error($this->db_link);
        }
        else{
          $this->query_data = array();
          $this->query_data_ = array();
          while($row = mysqli_fetch_assoc($this->query_res)){ 
            $this->query_data[] = $row;
          }
          if ($pnum){
            $first_row = $pnum*PAGE_LIMIT-PAGE_LIMIT;
            $this->query_data_['data'] = array_slice($this->query_data, $first_row, PAGE_LIMIT);
            $this->query_data_['count'] = count($this->query_data);
          }
          else{
            $this->query_data_ = $this->query_data;
            $this->query_data_[0]['count'] = count($this->query_data);
          }
          return $this->query_data_;
        }
      }
    }
    function query($sql){
      if (is_string($sql) && !empty($sql)){
        $sql = str_replace(array(';','"'),'',$sql);
        $this->query_res = mysqli_query($this->db_link, $sql);
        if ($this->query_res){
          if (strstr($sql, "insert")){
            // Вернутьь ID поледней записи
            return mysqli_insert_id($this->db_link); 
          }
          else{
            return 'true';
          }
        }
        else{
          return mysqli_error($this->db_link);
        }
      }
    }
  }
?>