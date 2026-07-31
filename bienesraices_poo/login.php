<?php
    require "includes/app.php";
    if (autenticado()) {
        header("location: /admin");
    }
    
    // require "includes/config/database.php";

    $errores = [];

    $db = conectarDB();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $email = mysqli_real_escape_string($db, filter_var($_POST["email"], FILTER_VALIDATE_EMAIL));
        $password =  mysqli_real_escape_string($db, $_POST["password"]);

        if (!$email)
            $errores[] = "El email es obligatorio o no es valido";
        if (!$password)
            $errores[] = "El password es obligatorio";
        
        if (empty($errores)) {
            $query = "SELECT * FROM usuarios WHERE email='$email'";
            $rows = mysqli_query($db, $query);
            if ($rows->num_rows > 0) {
                $usuario = mysqli_fetch_assoc($rows);
                $auth = password_verify($password, $usuario["password"]);
                if ($auth) {
                    session_start();
                    $_SESSION["usuario"] = $usuario["email"];
                    $_SESSION["logged"] = true;
                    header("location: /admin");
                }
                else 
                    $errores[] = "El password no es correcto.";
            }
            else
                $errores[] = "El usuario no existe";
        }
        
    }
    
    incluirTemplate("header");
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesion</h1>
    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach ?>
    <form method="POST" class="formulario">
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Tu Email">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Tu Password">
        </fieldset>
        <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
    </form>

</main>

<?php
    incluirTemplate("footer");
?>