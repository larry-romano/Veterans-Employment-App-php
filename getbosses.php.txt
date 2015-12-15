<?php
//returns a list of all employers as a json array
//receives empty http post
//returns a json array of employers and their attributes

require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      class Profile {
          // Creating some properties (variables tied to an object)
          public $id;
          public $name;
          public $title;
          public $company;
          public $address;
          public $phone;
          public $email;
          public $description;

          // Assigning the values
          public function __construct($uid, $name, $title, $company, $address, $phone, $email, $description)
          {
              $this->id          = $uid;
              $this->name        = $name;
              $this->title       = $title;
              $this->company     = $company;
              $this->address     = $address;
              $this->phone       = $phone;
              $this->email       = $email;
              $this->description = $description;
          }
      }

      $query = "SELECT * FROM employerdetails;";

      $stmt = $dbh->prepare($query);
      $stmt->execute();

      $profile = $stmt->fetchAll(PDO::FETCH_OBJ);

      $howmany = count($profile);
      if ($howmany > 0){
        foreach ($profile as $g_info) {
           // Retrieve contents of each row
           $profileList[] = new Profile($g_info->userid,
                                        $g_info->name,
                                        $g_info->title,
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
