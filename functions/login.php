<?php
  require_once "../config/db.php";

  if ($_SERVER["REQUEST_METHOD"] === "POST")
  {
    if(isset($_POST["correo"]) && isset($_POST["contrasena"]))
    {
      $sql = "SELECT nombre, email, rol FROM usuarios WHERE email = ? AND password = ?";
      $statement = mysqli_prepare($conexion, $sql);
      $contrasena_segura = md5($_POST["contrasena"]);
      mysqli_stmt_bind_param($statement, "ss", $_POST["correo"], $contrasena_segura);

      // Verifica que la consulta se haya ejecutado correctamente
      if (!mysqli_stmt_execute($statement))
      {
        echo "Error al ejecutar la consulta.";
        exit();
      }
  
      $query = mysqli_stmt_get_result($statement);
      $result = mysqli_fetch_assoc($query);

      // echo mysqli_num_rows($query);
      // echo $contrasena_segura;
      // exit();
  
      if (mysqli_num_rows($query) == 1)
      {
        session_start();
        $_SESSION["user"] = $result["nombre"];
        $_SESSION["user_email"] = $result["email"];
        $_SESSION["user_rol"] = $result["rol"];
        $_SESSION["user_times"] = 0;

        header("Location: ../index.php");
        exit();
      }
      else
      {
        header("Location: ../pages/login-page.php?status=1");
        exit();
      }
      
    }
  }
  header("Location: ../index.php");
?>