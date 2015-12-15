<?php
//returns a list of all skills in the skills table (all skills that can be chosen by either a veteran or an employer)
//receives an empty http post request
//returns complete skill lis
require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
           $query = "SELECT * FROM skills";

           $stmt = $dbh->prepare($query);

           $stmt->execute();
           $skills = $stmt->fetchAll(PDO::FETCH_OBJ);
           $hm = count($skills);

           if ($hm > 0){
             foreach ($skills as $g_info) {
                $skillList[] = $g_info->skill;
             }
           }
      echo json_encode($skillList);
    } catch(PDOException $e) {
           die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }


?>
