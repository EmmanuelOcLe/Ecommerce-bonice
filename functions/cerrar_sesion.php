<?php
  session_start();

  if (!isset($_SESSION["user"]))
  {
    header("Location: ../index.php");
    exit();
  }

  session_unset();
  session_destroy();

  setcookie("user_session", "finish", time() + 3600, "/");

  header("Location: ../index.php");
  exit();
?>