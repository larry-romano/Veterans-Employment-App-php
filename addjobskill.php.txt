<?php
   //adds a list of skills to be associated with a job
   //receives the skill list, the length of the list, and a job id associated with the list via http post 
   //returns a json object when skill is added

require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      //simple php class to verify successful addition of at least one skill
      class Skills { 
          public $id;

          public function __construct($uid)
          {
              $this->id        = $uid;
          }
      }
      $id =      $_POST["id"];
      $numskills = $_POST["numskills"];
      $month = "0";
      for($i = 0; $i < $numskills; $i++){
        //if the skill exists already, delete it
        $query = "DELETE FROM jobskills WHERE jobid = :id AND skill = :s;";

        $stmt = $dbh->prepare($query);
        //build a string to catch each skill in the list
        $key = "skill" . $i;
        $skill =   $_POST[$key];
        $stmt->bindParam('id', $id);
        $stmt->bindParam('s',  $skill);

        $stmt->execute();
        //add the skill
        $query = "INSERT INTO jobskills ".
                "(jobid, skill, months) ".
                "VALUES (:i,:s,:m);";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('i',  $id);
        $stmt->bindParam('s',  $skill);
        $stmt->bindParam('m',  $month);

        $stmt->execute();
      }  //end for loop
      $query = "SELECT * FROM jobskills WHERE jobid = :id";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('id', $id);
      $stmt->execute();
       
      $skills = $stmt->fetchAll(PDO::FETCH_OBJ);

      $howmany = count($skills);
      if ($howmany > 0){
        foreach ($skills as $g_info) {
           // Retrieve contents of each row and assign the job id to the confirmation object
           $skillList[] = new Skills($g_info->userid);
        }
      }
      //convert object array to json array and return
      echo json_encode($skillList);


    } catch(PDOException $e) {
        die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }

?>


