<?php
  session_start();
  require_once 'pdo.php';
  require_once 'util.php';
  require_once 'head.php';
  $stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['profile_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for profile_id';
      header( 'Location: index.php' ) ;
      return;
  }
  $n = htmlentities($row['first_name']);
  $e = htmlentities($row['last_name']);
  $email = htmlentities($row['email']);
  $p = htmlentities($row['headline']);
  $m = htmlentities($row['summary']);

  $pos=loadPos($pdo,$_REQUEST['profile_id']);
  $years=array();
  $descs=array();
  $length1=count($pos);
  if($length1!=0){
    for($i=1;$i<$length1+1;$i++){
      array_push($years,$pos[$i-1]['year']);
      array_push($descs,$pos[$i-1]['description']);
    }
  }

  $edu=loadEdu($pdo,$_REQUEST['profile_id']);
  $eduyears=array();
  $schools=array();
  $length2=count($edu);
  if($length2!=0){
    for($i=1;$i<$length2+1;$i++){
      array_push($eduyears,$edu[$i-1]['year']);
      array_push($schools,$edu[$i-1]['name']);
    }
  }

 ?>

<!-- ============================================================================================================================ -->

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Revanth Rokkam</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
      <h1>Profile Information</h1>
      <p>First Name: <?php echo $n; ?></p>
      <p>Last Name: <?php echo $e; ?></p>
      <p>Email: <?php echo $email; ?></p>
      <p>Headline:<br> <?php echo $p; ?></p>
      <p>Summary:<br> <?php echo $m; ?></p>
      <?php
        if($length1!=0){
          echo "Position: \n";
          echo "<ul>";
          for($i=1;$i<$length1+1;$i++){
            echo "<li>".htmlentities($years[$i-1])." / ".htmlentities($descs[$i-1])."</li>";
          }
          echo "</ul>";
        }
       ?>
       <?php
         if($length2!=0){
           echo "Education: \n";
           echo "<ul>";
           for($i=1;$i<$length2+1;$i++){
             echo "<li>".htmlentities($eduyears[$i-1])." / ".htmlentities($schools[$i-1])."</li>";
           }
           echo "</ul>";
         }
        ?>
      <a href="index.php">Done</a>
    </div>
  </body>
</html>
