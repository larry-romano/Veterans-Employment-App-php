<?php
//returns a list of all jobs in the database
//receives an empty http post request
//returns a json array of all jobs and each job's attributes


require_once("connect_safer.php");
$dbh = ConnectDB();


    try{
      class Job {
          // Creating some properties (variables tied to an object)
          public $id;
          public $title;
          public $company;
          public $description;
          public $contact;
          public $address;
          public $phone;
          public $email;
          public $url;
          public $deadline;
          public $applymethod;
          public $status;
          public $skills = array();
          public $index = 0;

          // Assigning the values
          public function __construct($uid, $title, $company, $description, $contact, $address, $phone, $email, $url, $deadline, $applyby, $status)
          {
              $this->id          = $uid;
              $this->title       = $title;
              $this->company     = $company;
              $this->description = $description;
              $this->contact     = $contact;
              $this->address     = $address;
              $this->phone       = $phone;
              $this->email       = $email;
              $this->url         = $url;
              $this->deadline    = $deadline;
              $this->applymethod = $applyby;
              $this->status      = $status;
          }
          public function setSkill($skill){
              $this->skills[$this->index] = $skill;
              $this->index = $this->index + 1;
          }

      }
      $query = "SELECT * FROM jobs;";

      $stmt = $dbh->prepare($query);

      $stmt->execute();

      $profile = $stmt->fetchAll(PDO::FETCH_OBJ);

      $howmany = count($profile);
      $i = 0;
      if ($howmany > 0){
        foreach ($profile as $g_info) {
           // Retrieve contents of each row
           $profileList[$i] = new Job($g_info->id,
                                      $g_info->title,
                                      $g_info->company,
                                      $g_info->description,
                                      $g_info->contact,
                                      $g_info->address,
                                      $g_info->phone,
                                      $g_info->email,
                                      $g_info->url,
                                      $g_info->deadline,
                                      $g_info->applyby,
                                      $g_info->status);
           $id = $g_info->id;
           $query = "SELECT * FROM jobskills WHERE jobid = :id;";

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
