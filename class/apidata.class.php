<?php 
require __DIR__ . "/../connection/connect.php";

class Dbapi{


    public function insertdata($code_societe,$percent,$count){
        global $conn;


        $req = 'insert into `data_twd` (`code_societe`,`label`,`y`) value ("'.$code_societe.'","v'.$count.'",'.$percent.')';
        $res = $conn->query($req);

    }
    
    public function truncate(){
        global $conn;
        $req = 'DELETE FROM `data_twd`';
        $res = $conn->query($req);
    }

    public function getdata(){
        global $conn;
        $req = 'select code_societe, label, y from `data_twd`'; 
        $res = $conn->query($req); 

        // foreach($res as $v){
        //     var_dump($v);

        // }
        return $res;
    }

}