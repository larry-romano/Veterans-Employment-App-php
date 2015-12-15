<?php
  //php for adding a job, receives an http post request
  //returns a simple json object indicating the job has been added.
require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      class Job {
          public $id;
          
          public function __construct($uid)
          {
              $this->id        = $uid;
          }
      }
	//query the database to see if a job posted by this employer with this id exists already
      $query = "SELECT * FROM jobs WHERE userid = :id AND id = :i;";

      $stmt = $dbh->prepare($query);
      $userid    =      $_POST["id"];
      $jobid     =      $_POST["jobid"];

      $stmt->bindParam('id', $userid);
      $stmt->bindParam('i',  $jobid);
      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
      
      //There should be 0 or 1 result
      $exists = count($result);

        if ($exists > 0){
          $query = "UPDATE jobs ".
                   "SET userid ".      "= :i, ".
                       "title ".       "= :t, ".
                       "company ".     "= :c, ".
                       "description ". "= :d, ".
                       "contact ".     "= :co, ".
                       "address ".     "= :a, ".
                       "phone ".       "= :p, ".
                       "email ".       "= :e, ".
                       "url ".         "= :u, ".
                       "deadline ".    "= :dl, ".
                       "applyby ".     "= :ab ".
                   "WHERE userid = :i AND id = :id;";
          $stmt = $dbh->prepare($query);
          $stmt->bindParam('id',  $jobid);
        }
        else {
          $query = "INSERT INTO jobs ".
                   "(userid, title, company, description, contact, address, phone, email, url, deadline, applyby) ".
                   "VALUES (:i,:t, :c, :d, :co, :a, :p, :e, :u, :dl, :ab);";
          $stmt = $dbh->prepare($query);
      }

      $title    =      $_POST["title"];
      $company  =      $_POST["company"];
      $descrip  =      $_POST["description"];
      $contact  =      $_POST["contact"];
      $address  =      $_POST["address"];
      $phone    =      $_POST["phone"];
      $email    =      $_POST["email"];
      $url      =      $_POST["url"];
      $deadline =      $_POST["deadline"];
      $applyby  =      $_POST["applymethod"];

      $stmt->bindParam('i',   $userid);
      $stmt->bindParam('t',   $title);
      $stmt->bindParam('c',   $company);
      $stmt->bindParam('d',   $descrip);
      $stmt->bindParam('co',  $contact);
      $stmt->bindParam('a',   $address);
      $stmt->bindParam('p',   $phone);
      $stmt->bindParam('e',   $email);
      $stmt->bindParam('u',   $url);
      $stmt->bindParam('dl',  $deadline);
      $stmt->bindParam('ab',  $applyby);

      $stmt->execute();

      $query = "SELECT * FROM jobs WHERE userid = :i AND description = :d AND title = :t;";

      $stmt = $dbh->prepare($query);
      $stmt->bindParam('i', $userid);
      $stmt->bindParam('d',  $descrip);
      $stmt->bindParam('t',  $title);
      $stmt->execute();


      // There should only be one, but this means if we get
      // more than one match we can find out easily.
      $profile = $stmt->fetchAll(PDO::FETCH_OBJ);
      $howmany = count($profile);
      if ($howmany > 0){
        foreach ($profile as $g_info) {
           // Retrieve contents of each row
           $profileList[] = new Job($g_info->id);
        }
      }
      echo json_encode($profileList);


    } catch(PDOException $e) {
        die ('PDO error in ListMatchingPhones(): ' . $e->getMessage() );
    }

?>

