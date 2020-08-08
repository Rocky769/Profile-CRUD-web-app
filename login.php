<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}
require_once "pdo.php";
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is meow123

// If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['failure'] = "Email and password are required";
        header("Location: login.php");
        return;
    }
    else if (strpos($_POST['email'], '@') === false) {
      $_SESSION['failure'] = "Email must have an at-sign (@)";
      header("Location: login.php");
      return;
    }
    else {
      $check = hash('md5', $salt.$_POST['pass']);
      $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
      $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ( $row !== false ) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
      }
      if ( $check == $stored_hash &&  $row !== false) {
            // Redirect the browser to game.php
            error_log("Login success ".$_POST['email']."\n", 3, 'error.log');
            $_SESSION['email']=$_POST['email'];
            header("Location: add.php");
            return;
        } else {
            $_SESSION['failure'] = "Incorrect password";
            error_log("Login fail ".$_POST['email']." $check"."\n", 3, 'error.log');
            header("Location: login.php");
            return;
        }
    }
  }
?>

<!-- ============================================================================================================================ -->

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Revanth Rokkam's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['failure']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<script type="text/javascript">
function doValidate() {
  console.log('Validating...');
  try {
    addr = document.getElementById('nam').value;
    pw = document.getElementById('id_1723').value;
    console.log("Validating addr="+addr);
    console.log("Validating pw="+pw);
    if (pw == null || pw == "" || addr=='' || addr==null) {
      alert("Both fields must be filled out");
      return false;
    }
    else if (addr.indexOf('@') == -1){
      alert("Invalid email address");
      return false;
    }
    return true;
  } catch(e) {
    return false;
  }
  return false;
}
</script>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
</div>
</body>
