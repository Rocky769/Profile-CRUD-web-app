<?php
session_start();
require_once 'pdo.php';
require_once 'util.php';
require_once 'head.php';
  if ( !isset($_SESSION['name']) || strlen($_SESSION['name']) < 1  ) {
    die('ACCESS DENIED');
  }
  if(isset($_POST['logout'])){
    header('Location: logout.php');
    return;
  }
  if(isset($_POST['addPos'])){

  }
  if (isset($_POST['first_name']) && isset($_POST['email']) && isset($_POST['last_name']) && isset($_POST['headline']) && isset($_POST['summary'])) {
    $msg=validateForm();
    $msg2=validatePos();
    $msg3=validateEdu();
    if(is_string($msg)){
      $_SESSION['error']=$msg;
      header('Location: add.php');
      return;
    }
    if(is_string($msg2)){
      $_SESSION['error']=$msg2;
      header('Location: add.php');
      return;
    }
    if(is_string($msg3)){
      $_SESSION['error']=$msg3;
      header('Location: add.php');
      return;
    }
    if(!is_string($msg) && !is_string($msg2) && !is_string($msg3)){
      $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');
      $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
      );
      $profile_id=$pdo->lastInsertId();
      $rank=1;
      for($i=1;$i<10;$i++){
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
          ':pid' => $profile_id,
          ':rank' => $rank,
          ':year' => $year,
          ':desc' => $desc
        ));
        $rank++;
      }
      for($i=1;$i<10;$i++){
        if(!isset($_POST['eduyear'.$i])){
          continue;
        }
        if(!isset($_POST['eduschool'.$i])){
          continue;
        }
        $year=$_POST['eduyear'.$i];
        $school=$_POST['eduschool'.$i];
        $stmt = $pdo->prepare("SELECT * FROM Institution WHERE name=:insti");
        $stmt->execute(array(
          ":insti"=>$school
        ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row===false){
          $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES ( :school)');
          $stmt->execute(array(
            ':school'=>$school
          ));
          $iid=$pdo->lastInsertId();
        }
        else{
          $iid=$row['institution_id'];
        }

        $stmt = $pdo->prepare('INSERT INTO Education (profile_id, rank, year, institution_id) VALUES ( :pid, :rank, :year, :iid)');
        $stmt->execute(array(
          ':pid' => $profile_id,
          ':rank' => $rank,
          ':year' => $year,
          ':iid' => $iid
        ));
        $rank++;
      }
      $_SESSION['success']='Profile added';
    }
  }

  if(isset($_POST['add']) && !isset($_SESSION['failure1'])){
    header('Location: index.php');
    return;
  }
?>

<!-- ============================================================================================================================ -->

<!DOCTYPE html>
<html>
<head>
<title>Revanth Rokkam's Resume Registry</title>
<?php require_once "bootstrap.php";require_once 'head.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="script.js"></script>
</head>
<body>
<div class="container">
<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Adding Profile for ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}
flashmessages();
?>
<form method="post">
<p>First name: <input type="text" name="first_name" size="60" id='fn'></p>
<p>Last name: <input type="text" name="last_name" size="60" id='ln'></p>
<p>Email: <input type="text" name="email" size="30" id='email'></p>
<p>Headline:<br> <input type="text" name="headline" size="80" id='hl'></p>
<p>Summary:<br> <textarea name="summary" rows="8" cols="80" id='sum'></textarea> </p>
<p>Education: <input type="submit" id="addEdu" value="+"></p>
<div id="edu">
</div>
<p>Position: <input type="submit" id="addPos" value="+"></p>
<div id="pos">
</div>
<input type="submit" name='add' value="Add New Entry"/>
<button type="submit" name="logout">Logout</button>
</form>
</div>
</body>
</html>
