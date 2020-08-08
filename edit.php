<?php
require_once "pdo.php";
require_once "util.php";
require_once 'head.php';
session_start();

if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

if (isset($_POST['first_name']) && isset($_POST['email']) && isset($_POST['last_name']) && isset($_POST['headline']) && isset($_POST['summary'])) {
  $msg=validateForm();
  if(is_string($msg)){
    $_SESSION['error'] = $msg;
    header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
    return;
  }
  $msg2=validatePos();
  if(is_string($msg2)){
    $_SESSION['error'] = $msg2;
    header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
    return;
  }
  else{
    $sql = "UPDATE Profile SET first_name = :first_name,
            last_name = :last_name, headline = :headline, summary = :summary,email = :email
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id' => $_GET['profile_id'],
        ':email' => $_POST['email']
    ));

    $sql = "DELETE FROM Position WHERE profile_id=:pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

    insertPos($pdo,$_REQUEST['profile_id']);

    $sql = "DELETE FROM Education WHERE profile_id=:pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

    insertEdu($pdo,$_REQUEST['profile_id']);

    $_SESSION['success'] = 'Profile updated';
    header( 'Location: index.php' ) ;
    return;
  }
}


$n = htmlentities($row['first_name']);
$e = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$p = htmlentities($row['headline']);
$m = htmlentities($row['summary']);
$profile_id = $row['profile_id'];

$positions=loadPos($pdo,$_REQUEST['profile_id']);
$education=loadEdu($pdo,$_REQUEST['profile_id']);

?>

<!-- ============================================================================================================================ -->

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Revanth Rokkam - Resume Registry</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="script.js"></script>
  </head>
  <body>
    <?php    echo "<h1>Editing Profile for ";
      echo htmlentities($_SESSION['name']);
      echo "</h1>\n";
      flashmessages(); ?>
    <form method="post">
      <p>First name: <input type="text" name="first_name" size="60" id='fn' value="<?php echo $n ?>"></p>
      <p>Last name: <input type="text" name="last_name" size="60" id='ln' value="<?php echo $e ?>"></p>
      <p>Email: <input type="text" name="email" size="30" id='email' value="<?php echo $email ?>"></p>
      <p>Headline:<br> <input type="text" name="headline" size="80" id='hl' value="<?php echo $p ?>"></p>
      <p>Summary:<br> <textarea name="summary" rows="8" cols="80" id='sum'><?php echo $m ?></textarea></p>
      <p>Education: <input type="submit" id="addEdu" value="+"></p>
      <div id="edu">
        <?php
          $countEdu=0;
          if(count($education)!==0){
            foreach ($education as $school) {
              $countEdu++;
              echo '<div class="school" id="school'.$countEdu.'" value="'.htmlentities($school['name']).'">';
              echo '<p>Year: <input type="text" name="eduyear'.$countEdu.'" value="'.htmlentities($school['year']).'"> ';
              echo '<input type="button" name="" onclick="$(\'#education'.$countEdu.'\').remove();return false;" value="-"></p><br>';
              echo '<p>School: <input type="text" name="eduschool'.$countEdu.'" value="'.htmlentities($school['name']).'"></p></div>';
            }
          }
         ?>
      </div>
      <p>Position: <input type="submit" id="addPos" value="+"></p>
      <div id="pos">
        <?php
          $countPos=0;
          if(count($positions)!==0){
            foreach ($positions as $position) {
              $countPos++;
              echo '<div class="position" id="position"'.$countPos.'value="'.htmlentities($position['year']).'">
              <p>Year: <input type="text" name="year'.$countPos.'" value="'.htmlentities($position['year']).'">
              <input type="button" name="" onclick="$(\'#position'.$countPos.'\').remove();return false;" value="-"></p><br>';

              echo '<textarea name="desc'.$countPos.'" rows="8" cols="80">'."\n";
              echo htmlentities($position['description'])."\n";
              echo '</textarea></div>';
            }
          }
         ?>
      </div>
      <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
    <p><input type="submit" onclick='return doValidate();' value="Save"/>
    <a href="index.php">Cancel</a></p>
    </form>
  </body>
</html>
