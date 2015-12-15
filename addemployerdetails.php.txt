<?php

//php for creating an employer profile
//receives an http post request and returns a json object indicating success

require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      //build a class containing the user id for the profile created.
      class Profile {
          public $id;
          public function __construct($uid)
          {
              $this->id        = $uid;
          }
      }
      //if a profile alraady exists for this user id, we delete it and add a new one
      $query = "DELETE FROM employerdetails WHERE userid = :id";

      $stmt = $dbh->prepare($query);

      $id =      $_POST["id"];

      $stmt->bindParam('id', $id);

      $stmt->execute();
      //insert a new row
      $query = "INSERT INTO employerdetails ".
               "(userid, name, title, company, address, phone, email, description) ".
               "VALUES (:i,:n,:t,:c,:a,:p,:e,:d);";

      $stmt = $dbh->prepare($query);

      $name        =   $_POST["name"];
      $title       =   $_POST["title"];
      $company     =   $_POST["company"];
      $address     =   $_POST["address"];
      $phone       =   $_POST["phone"];
      $email       =   $_POST["email"];
      $description =   $_POST["description"];

      $stmt->bindParam('i',  $id);
      $stmt->bindParam('n',  $name);
      $stmt->bindParam('t',  $title);
      $stmt->bindParam('c',  $company);
      $stmt->bindParam('a',  $address);
      $stmt->bindParam('p',  $phone);
      $stmt->bindParam('e',  $email);
      $stmt->bindParam('d',  $description);

      $stmt->execute();

      $query = "SELECT * FROM employerdetails WHERE userid = :id";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('id', $id);
      $stmt->execute();


      // There should only be one, but this means if we get
      // more than one match we can find out easily.
      $profile = $stmt->fetchAll(PDO::FETCH_OBJ);

      $howmany = count($profile);
      if ($howmany > 0){
        foreach ($profile as $g_info) {
           // Retrieve contents of each row and convert to php object
           $profileList[] = new Profile($g_info->userid);
        }
      }
	//convert php object to json and return
      echo json_encode($profileList);


    } catch(PDOException $e) {
        die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }

?>

