<!DOCTYPE html>
<!--
- Paxinar con 25 elementos por paxina.

-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Consulta con parámetros</title>
        <link href="estilo.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php
        print<<<HTML
                           
        <table style='border-collapse: collapse;'>              
            <th style='width:20%;'>Code</th>
            <th style='width:20%;'>Name</th>
            <th style='width:20%;'>Continent</th>
            <th style='width:20%;'>Region</th>
            
HTML;
        /* Incluimos base de datos */
        session_start();
        require_once 'conexion.php';

        $numRexistrosPax = 25;
        $paxina = 1;

        //No caso de que esteamos noutra paxina diferente actualizamos valor $paxina
        if (array_key_exists('pax', $_GET)) {
            $paxina = $_GET['pax'];
        }
        //Definimos e executamos consulta para saber cantos rexistros van por páxina
        $stmt1 = $pdo->query('SELECT COUNT(*) FROM country ');
        $totalRexistros = $stmt1->fetchColumn();

        $totalPaxinas = ceil($totalRexistros / $numRexistrosPax);


        //definimos consulta para ver os rexistros da BD
        $sql = 'SELECT Code, Name, Continent, Region FROM country '
                . 'LIMIT ' . (($paxina - 1) * $numRexistrosPax) . ', ' . $numRexistrosPax;

        $stmt = $pdo->prepare($sql);

        $stmt->execute();
        while ($row = $stmt->fetch()) {
            print<<<HTML
            <tr >
              <td  style='border: orange 3px solid;'>$row[Code]</td>
              <td  style='border: orange 3px solid;'>$row[Name]</td>
              <td  style='border: orange 3px solid;'>$row[Continent]</td>
              <td style='border: orange 3px solid;'>$row[Region]</td>          
           </tr>
HTML;
        }

        //Fechamos a conexión
        $db = null;

        echo "</table></br></br><div style='text-align: center;' id='paxinado'>";
        for ($i = 0; $i < $totalPaxinas; $i++) {
            echo '<a href="paxinar.php?pax=' . ($i + 1) . '">' . ($i + 1) . '</a> | ';
        }
        echo "</div>";
        ?>
    </body>
</html>