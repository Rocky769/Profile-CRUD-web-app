<?php session_start();require_once 'util.php';require_once 'head.php'; ?>

<!-- ============================================================================================================================ -->

<!DOCTYPE html>
<html>
<head>
<title>31c8c542 - Resume Registry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
  <?php
  if(isset($_SESSION['name'])){
    echo "<h1>Welcome to Resume Registry: ".$_SESSION['name']."</h1>";
  }
  else{
    echo "<h1>Revanth Rokkam's Resume Registry</h1>";
  }
  require_once 'pdo.php';
  flashmessages();
  if(isset($_SESSION['name'])){
    echo "<p><a href='logout.php'>Logout</a></p>";
  }
  else{
    echo "<p><a href='login.php'>Please log in</a></p>";
  }
  $stmt=$pdo->query("SELECT email,first_name,last_name,headline,user_id,profile_id FROM Profile");
  $rowcheck=$stmt->fetch(PDO::FETCH_ASSOC);
  if ($rowcheck===false){
    echo "<p>No rows found</p>";
  }
  else{
    echo "<table border='1'>
    <thead><tr>
    <th>Name</th>
    <th>Headline</th>";
    if(isset($_SESSION['name'])){
      echo "<th>Action</th>";
    }
    echo "</tr></thead><tbody>";
    echo "<tr><td><a href='view.php?profile_id=".$rowcheck['profile_id']."'>".htmlentities($rowcheck['first_name'])." ".htmlentities($rowcheck['last_name'])."</a></td><td>".htmlentities($rowcheck['headline'])."</td>";
    if(isset($_SESSION['name'])){
      echo "<td><a href='edit.php?profile_id=".$rowcheck['profile_id']."'>Edit</a> / <a href='delete.php?profile_id=".$rowcheck['profile_id']."'>Delete</a></td></tr>";
    }
    else{
      echo "</tr>";
    }
    while ($rowcheck=$stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td><a href='view.php?profile_id=".$rowcheck['profile_id']."'>".htmlentities($rowcheck['first_name'])." ".htmlentities($rowcheck['last_name'])."</a></td><td>".htmlentities($rowcheck['headline'])."</td>";
      if(isset($_SESSION['name'])){
        echo "<td><a href='edit.php?profile_id=".$rowcheck['profile_id']."'>Edit</a> / <a href='delete.php?profile_id=".$rowcheck['profile_id']."'>Delete</a></td></tr>";
      }
      else{
        echo "</tr>";
      }
    }
    echo "</tbody></table><br>";
  }
  if(isset($_SESSION['name'])){
    echo "<p><a href='add.php'>Add New Entry</a></p>";
  }
   ?>

</div>
</body>
