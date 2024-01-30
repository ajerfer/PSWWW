<?php
    function connect(){
        $user="root";
        $pass="";
        $server="localhost";
        $db="prueba";
        $con=mysqli_connect($server,$user,$pass,$db,3306);
        if(!$con){
            die("No connection".mysqli_error());
        }
        return $con;
    }
?>
