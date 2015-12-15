<?php
//returns a veteran profile as a json object
//receives the user id associated with the vet's account
//returns a json array of profile attributes

require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      class Profile {
           
          public $id;
          public $name;
          public $age;
          public $address;
          public $sex;
          public $branch;
          public $rank;
          public $description;
          public $phone;
          public $email;
          public $skills = array();
          public $index = 0;

           
          public function __construct($uid, $name, $age, $address, $sex, $branch, $rank, $description, $phone, $email)
          {
              $this->id          = $uid;
              $this->name        = $name;
              $this->age         = $age;
              $this->address     = $address;
              $this->sex         = $sex;
              $this->branch      = $branch;
              $this->rank        = $rank;
              $this->description = $description;
              $this->phone       = $phone;
              $this->email       = $email;
          }
          public function setSkill($skill){
              $this->skills[$this->index] = $skill;
              $this->index = $this->index + 1;
          }
      }
      $id     =      $_POST["id"];

      $query = "SELECT * FROM vetdetails WHERE userid = :id";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('id', $id);
      $stmt->execute();


      // There should only be one, but this means if we get
      // more than one match we can find out easily.
      $profile = $stmt->fetchAll(PDO::FETCH_OBJ);

      $howmany = count($profile);
      $i = 0;
      if ($howmany > 0){
        foreach ($profile as $g_info) {
           // Retrieve contents of each row
           $profileList[$i] = new Profile($g_info->userid,
                                          $g_info->name,
                                          $g_info->age,
                                          $g_info->address,
                                          $g_info->sex,
                                          $g_info->branch,
                                          $g_info->rank,
                                          $g_info->description,
                                          $g_info->phone,
                                          $g_info->email);
          $query = "SELECT * FROM vetskills WHERE userid = :id;";

          $stmt = $dbh->prepare($query);
          $stmt->bindParam('id', $id);

          $stmt->execute();
          $skills = $stmt->fetchAll(PDO::FETCH_OBJ);
          $hm = count($skills);

          if ($hm > 0){
             foreach ($skills as $g_info) {
                $profileList[$i]->setSkill($g_info->skill);
             }
          }
          $i++;
        }
      }
      echo json_encode($profileList);


    } catch(PDOException $e) {
        die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }

?>
