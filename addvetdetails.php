<?php
//adds a veteran profile
//receives all deetails associated with the veteran, and the user id associated with the veteran
//returns a simple json object verifying addition of profile

require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      class Profile {          
          public $id;
           
          public function __construct($uid)
          {
              $this->id        = $uid;
          }
      }

      $query = "DELETE FROM vetdetails WHERE userid = :id";

      $stmt = $dbh->prepare($query);

      $id =      $_POST["id"];
      $stmt->bindParam('id', $id);
      $stmt->execute();

      $query = "INSERT INTO vetdetails ".
               "(userid, name, age, address, sex, branch, rank, description, phone, email) ".
               "VALUES (:i,:n,:a, :ad, :s, :b, :r, :d, :p, :e);";

      $stmt = $dbh->prepare($query);

      $name        =      $_POST["name"];
      $age         =      $_POST["age"];
      $address     =      $_POST["address"];
      $sex         =      $_POST["sex"];
      $branch      =      $_POST["branch"];
      $rank        =      $_POST["rank"];
      $description =      $_POST["description"];
      $phone       =      $_POST["phone"];
      $email       =      $_POST["email"];

      $stmt->bindParam('i',  $id);
      $stmt->bindParam('n',  $name);
      $stmt->bindParam('a',  $age);
      $stmt->bindParam('ad', $address);
      $stmt->bindParam('s',  $sex);
      $stmt->bindParam('b',  $branch);
      $stmt->bindParam('r',  $rank);
      $stmt->bindParam('d',  $description);
      $stmt->bindParam('p',  $phone);
      $stmt->bindParam('e',  $email);

      $stmt->execute();

      $query = "SELECT * FROM vetdetails WHERE userid = :id";

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
           $profileList[] = new Profile($g_info->userid);
        }
      }
      echo json_encode($profileList);


    } catch(PDOException $e) {
        die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }

?>
