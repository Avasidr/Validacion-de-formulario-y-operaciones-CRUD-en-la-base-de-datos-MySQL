<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario de Registro</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <div id="group">
            <form method="post" action="">

                <h2>Formulario de Registro</h2>

                <label for="firstName">Nombre <span>(*)</span></label>
                <input type="text" id="firstName" name="firstName" required>

                <label for="lastName1">Primer Apellido <span>(*)</span></label>
                <input type="text" id="lastName1" name="lastName1" required>

                <label for="lastName2">Segundo Apellido</label>
                <input type="text" id="lastName2" name="lastName2">

                <label for="email">Email <span>(*)</span></label>
                <input type="email" id="email" name="email" required>

                <label for="login">Login <span>(*)</span></label>
                <input type="text" id="login" name="login" required>

                <label for="password">Password <span>(*) (Entre 4 y 8 caracteres)</span></label>
                <input type="password" id="password" name="password" required>

                <input type="submit" id="form-btn" name="submit" value="Enviar">


            </form>

        <?php

            // Conexión con base de datos
            $servername = "localhost";
            $username = "root";
            $dbpassword = "";
            $dbname = "laboratoriosql";


            //Crear conexión
            $conn = new mysqli($servername, $username, $dbpassword, $dbname);



            // Comprobar conexión
            if($conn->connect_error) {
                die("Conection failed: " . $conn->connect_error);
            }

            //Validación de los datos y escritura en la BBDD
            if(isset($_POST['submit'])) {

                $firstName = $_POST['firstName'];
                $lastName1 = $_POST['lastName1'];
                $lastName2 = $_POST['lastName2'];
                $email = $_POST['email'];
                $login = $_POST['login'];
                $password = $_POST['password'];


                $password = $_POST['password'];

                // Verificar longitud de contraseña
                if(strlen($password) < 4 || strlen($password) > 8) {
                    echo '<script>alert("La contraseña debe tener entre 4 y 8 caracteres.");</script>';
                    exit();
                }

                // Comprobar que los datos no existen en la base de datos
                $checkQuery = "SELECT COUNT(*) AS count FROM registro WHERE EMAIL = '$email'";
                $checkResult = $conn->query($checkQuery);
                $row = $checkResult->fetch_assoc();
                if($row['count'] > 0) {
                    echo '<script>alert("El email ya está en uso. Por favor, elije otro.");</script>';
                    exit();
                }
                $checkQuery = "SELECT COUNT(*) AS count FROM registro WHERE USUARIO = '$login'";
                $checkResult = $conn->query($checkQuery);
                $row = $checkResult->fetch_assoc();
                if($row['count'] > 0) {
                    echo '<script>alert("El login ya está en uso. Por favor, elije otro.");</script>';
                    exit();
                }

                // Escribir los datos en la BBDD
                $sql = "INSERT INTO registro (NOMBRE, APELLIDO1, APELLIDO2, EMAIL, USUARIO, CLAVE)
                VALUES ('$firstName', '$lastName1', '$lastName2', '$email', '$login', '$password')";

                if ($conn->query($sql) === TRUE) {
                    echo "Nuevo registro creado correctamente.";

                    // Mostrar botón de consulta
                    echo '<form method="post" action="">
                        <input type="submit" name="consulta" value="Consultar">
                        </form>';
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

            }

            // Consulta y visualización de usuarios registrados
            if(isset($_POST['consulta'])) {
                $query = "SELECT * FROM registro";
                $result = $conn->query($query);

                if($result->num_rows > 0) {
                    echo "<h3>Lista de usuarios registrados: </h3>";
                    echo "<ul>";
                    while($row = $result->fetch_assoc()) {
                        echo "<li>" . $row['NOMBRE'] . " " . $row['APELLIDO1'] . " " . $row['APELLIDO2'] . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "No se encontraron usuarios registrados.";
                }
            }
            

            $conn->close();
        ?>

        </div>


    </body>

</html>







