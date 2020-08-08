<?php
  require_once 'pdo.php';

  function flashmessages(){
    if (isset($_SESSION['success'])) {
        echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
  }

  function validateForm(){
    $msg=true;
    if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['email'])<1 || strlen($_POST['summary'])<1){
      $msg='All values are required';
    }
    else if(strpos($_POST['email'],'@')===false){
      $msg='Email must contain @';
    }
    return $msg;
  }

  function validatePos(){
    $msg=true;
    for($i=0;$i<9;$i++){
      if(!isset($_POST['year'.$i])){
        continue;
      }
      if(!isset($_POST['desc'.$i])){
        continue;
      }
      if(strlen($_POST['year'.$i])<1 || strlen($_POST['desc'.$i])<1){
        $msg='All fields are required';
      }
      if(!is_numeric($_POST['year'.$i])){
        $msg='Position year must be numeric';
      }
    }
    return $msg;
  }

  function validateEdu(){
    $msg=true;
    for($i=0;$i<9;$i++){
      if(!isset($_POST['eduyear'.$i])){
        continue;
      }
      if(!isset($_POST['eduschool'.$i])){
        continue;
      }
      if(strlen($_POST['eduyear'.$i])<1 || strlen($_POST['eduschool'.$i])<1){
        $msg='All fields are required';
      }
      if(!is_numeric($_POST['eduyear'.$i])){
        $msg='Education year must be numeric';
      }
    }
    return $msg;
  }

  function loadPos($pdo,$profile_id){
    $stmt = $pdo->prepare("SELECT * FROM Position where profile_id = :prof ORDER BY rank");
    $stmt->execute(array(":prof" => $profile_id));
    $pos=array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      array_push($pos,$row);
    }
    return $pos;
  }

  function loadEdu($pdo,$profile_id){
    $stmt = $pdo->prepare("SELECT year,name FROM Education JOIN Institution on Education.institution_id=Institution.institution_id where profile_id = :prof ORDER BY rank");
    $stmt->execute(array(":prof" => $profile_id));
    $pos=array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      array_push($pos,$row);
    }
    return $pos;
  }

  function insertPos($pdo,$profile_id){
    $rank=1;
    for($i=1;$i<=9;$i++){
      if(!isset($_POST['year'.$i])){
        continue;
      }
      if(!isset($_POST['desc'.$i])){
        continue;
      }
      $year=$_POST['year'.$i];
      $desc=$_POST['desc'.$i];
      $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
      $stmt->execute(array(
        ':pid' => $_REQUEST['profile_id'],
        ':rank' => $rank,
        ':year' => $year,
        ':desc' => $desc
      ));
      $rank++;
    }
  }

  function insertEdu($pdo,$profile_id){
    $rank=1;
    for($i=1;$i<=9;$i++){
      if(!isset($_POST['eduyear'.$i])){
        continue;
      }
      if(!isset($_POST['eduschool'.$i])){
        continue;
      }
      $year=$_POST['eduyear'.$i];
      $desc=$_POST['eduschool'.$i];
      $insti_id=false;
      $stmt = $pdo->prepare("SELECT institution_id FROM Institution WHERE name=':name'");
      $stmt->execute(array(
        ':name'=>$desc
      ));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ( $row === false ) {

      }
      $rank++;
    }
  }

 ?>
