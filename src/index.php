<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Hello there</title>
        <style>
            body {
                font-family: "Arial", sans-serif;
                font-size: larger;
            }

            .center {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 50%;
            }
        </style>
    </head>
    <body>
        <img src="https://tech.osteel.me/images/2020/03/04/hello.gif" alt="Hello there" class="center">
        <?php
            try {
                $mbd = new PDO('mysql:host=mysql;dbname=demo', 'root', 'root');
                foreach($mbd->query('SELECT * from prueba') as $fila) {
                    print_r($fila);
                }
                $mbd = null;
            } catch (PDOException $e) {
                print "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        ?>
    </body>
</html>