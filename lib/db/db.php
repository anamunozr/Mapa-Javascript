<?php

date_default_timezone_set('America/Santiago');

require_once('lib/log4php/Logger.php');
include_once("config.php");

global $LOGS_CONFIG;
Logger::configure($LOGS_CONFIG);

class MySQL_Database{

        var $lastQuery = "";
        private $logger;
        private $conn;


        function MySQL_Database(){
            $this->logger = Logger::getRootLogger();

            global $DB_HOST,$DB_USER,$DB_PASS,$DB_NAME;

            try {
                $this->conn = new PDO("mysql:host=".$DB_HOST.";dbname=".$DB_NAME."",$DB_USER,$DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            }
            catch (PDOException $ex) {
                echo $ex->getMessage();
                exit;
            }

            setlocale(LC_ALL,"es_CL.UTF-8");
        }

        function ExecuteQuery($szQuery,$debug=false){
            $tResult 			 = array();
            $tResult['status']   = 0;
            $tResult['rows'] 	 = 0;
            $tResult['data']	 = array();
            $tResult['error']	 = "";
            $this->lastQuery     = $szQuery;
            $szQuery 			 = trim($szQuery);

            if($debug){
                $this->logger->debug($szQuery);
            }

            $tDbResult = $this->conn->prepare($szQuery);

            if($tDbResult->execute()){

                $tResult['status'] = 1;
                if(strpos($szQuery,"INTO OUTFILE")===FALSE){

                    if(strpos($szQuery,"SELECT")===0 || strpos($szQuery,"SHOW")===0){

                        $tResult['rows'] = $tDbResult->rowCount();
                        $tResult['data'] = $tDbResult->fetchAll(PDO::FETCH_ASSOC);

                    }else{

                        $tResult['data'][0]['status'] = $tResult['status'];
                        //$tResult['data'][0]['id']     = $tDbResult->lastInsertId();
                        $tResult['data'][0]['id']     = $this->conn->lastInsertId();
                        $tResult['rows']              = $tDbResult->rowCount();

                    }
                }

            }else{

                print_r($tDbResult -> errorInfo ());
                $tResult['error'] = $tDbResult -> errorInfo ()[2];
                $this->logger->error("ERROR: ".$tDbResult -> errorInfo ()[2]);
                $this->logger->error("QUERY: ".$szQuery);

            }
            return $tResult;

        }


        function ExecuteFastQuery($szQuery){
            $tDbResult = $this->conn->prepare($szQuery);
            if($tDbResult->execute()){
             return true;
             }else{
             return false;
            }
        }

         function startTransaction(){
                 $szQuery = "START TRANSACTION";
                return $this->ExecuteQuery($szQuery);
         }

         function Commit(){
                 $szQuery = "COMMIT";
                return $this->ExecuteQuery($szQuery);
         }
         function Rollback(){
                 $szQuery = "ROLLBACK";
                return $this->ExecuteQuery($szQuery);
         }
		 function Quote($string){
            return $this->conn->quote($string);
         }
         function Close(){
                $this->conn = null;
         }
    }
?>
