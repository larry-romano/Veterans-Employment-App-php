<?php
//find an employer profile and return it as a json array
//receives user id associated with employer
//returns json array of employer attributes

require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      class Profile {
          public $id;
          public $name;
          public $title;
          public $company;
          public $address;
          public $phone;
          public $email;
          public $description;

 
          public function __construct($uid, $name, $title, $company, $address, $phone, $email, $description)
          {
              $this->id          = $uid;
              $this->name        = $name;
              $this->title       = $title;
              $this->company     = $company;
              $this->address     = $address;
              $this->phone       = $phone;
              $this->email       = $email;
              $this->description = $description;          }
      }

      $id     =      $_POST["id"];

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
           // Retrieve contents of each row
           $profileList[] = new Profile($g_info->userid,
                                        $g_info->name,
                                        $g_info->title,
                                        $g_info->company,
                                        $g_info->address,
                                        $g_info->phone,
                                        $g_info->email,
                                        $g_info->description);
        }
      }
      echo json_encode($profileList);


    } catch(PDOException $e) {
        die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }

?>
