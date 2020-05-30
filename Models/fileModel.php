<?php
class fileModel {
    private $connection = "";

    public function __construct() {
        $this->connection = new mysqli("localhost","root","****","whiterabit");
        $this->checkConnection();
    }

    public function checkConnection()
    {
        if ($this->connection->connect_errno) {
            throw new Exception("Failed to connect to MySQL: " . $this->connection->connect_error);
        }
    }

    public function insertFile($file_name = "")
    {
        if(empty($file_name))
            return false;
        $time = time();
        $query = "INSERT INTO files (file,created_time,status) VALUES('$file_name' , $time , 1)";
        if($insert_id = $this->execute($query)){
            $this->insertHistory($insert_id , 1 , $time);
            return $insert_id;
        }
        return false;
    }

    public function getFiles( $offset = 0)
    {
        $query = "SELECT id,file,created_time FROM files where status = 1 LIMIT $offset,25";
        $result = $this->connection->query($query);
        if($result)
            return $result->fetch_all(MYSQLI_ASSOC);
        return [];
    }

    public function deleteFile($file_id = 0)
    {
        if(!empty($file_id)){
            $query = "UPDATE files SET status =0 WHERE id = $file_id";
            if($this->connection->query($query) === TRUE){
                return $this->insertHistory($file_id , 2 , time());
            }
            return false;
        }
    }

    public function insertHistory($file_id , $action_type = 1 , $time)
    {
        $query = "INSERT INTO history (file_id, action_type, action_time) VALUES($file_id , $action_type , $time)";
        if($insert_id = $this->execute($query)){
            return $insert_id;
        }
        return false;        
    }

    public function getHistory($offset = 0)
    {
        $query = "SELECT h.action_type,h.action_time,f.file FROM history h LEFT JOIN files f ON h.file_id = f.id LIMIT $offset,25";
        $result = $this->connection->query($query);
        if($result)
            return $result->fetch_all(MYSQLI_ASSOC);
        return [];
    }

    private function execute($query)
    {
        if($this->connection->query($query) === TRUE){
            $insert_id = $this->connection->insert_id ?? true;
            return $insert_id;
        } else {
            throw new Exception($this->connection->connect_error);
        }
    }
}