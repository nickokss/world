 <?php
 //inicializar variables
        session_start();
        $senResultados = true;
        $numRexistrosPax = 25;
        $paxina = 0;
        $con = $reg = $nom = '';
        //No caso de que esteamos noutra paxina diferente actualizamos valor $paxina
        if (array_key_exists('pax', $_GET)) {
            $paxina = $_GET['pax'];
            $_SESSION['pax'] = $paxina;
        }
        //Executase a primeira vez que entramos na páxina
        if (!array_key_exists('pax', $_GET)) {
            $_SESSION['con'] = '';
            $_SESSION['reg'] = '';
            $_SESSION['nom'] = '';
        }
        //Executase cando lle damos o boton filtrar
        if (array_key_exists('filtrar', $_POST)) {   
            $_SESSION['con'] = filter_input(INPUT_POST, 'continente', FILTER_SANITIZE_STRING);
            $_SESSION['reg'] = filter_input(INPUT_POST, 'rexion', FILTER_SANITIZE_STRING);
            $_SESSION['nom'] = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        }
        
        $paxina = $_SESSION['pax'] ;
        $con = $_SESSION['con'];
        $reg = $_SESSION['reg'];
        $nom = $_SESSION['nom'];
        ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Consulta simple</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <h2 class="center">Listado de Paises</h2>        
        <form class="center" method='POST' action='<?php echo htmlentities($_SERVER['SCRIPT_NAME']); ?>'>
            <span>Escolle a opción para ordenar</span><br><br>
            <input type="checkbox" name="continente" value="Continent" 
            <?php
            if ($_SESSION['con'] != '') {
                echo 'checked';
            }
            ?>
                   > Continente
            <input type="checkbox" name="rexion" value="Region" <?php
            if ($_SESSION['reg'] != '') {
                echo 'checked';
            }
            ?>
                   > Rexión
            <input type="checkbox" name="nome" value="Name" <?php
            if ($_SESSION['nom'] != '') {
                echo 'checked';
            }
            ?>
                   > Nome
            <input type="submit" name="filtrar" value="Filtra"><br>
            
        </form> 
        <?php
        print<<<HTML
        <table>
            <th>Code</th>
            <th>Name</th>
            <th>Continent</th>
            <th>Region</th>
HTML;

        /* Incluimos base de datos */
        require_once 'conexion.php';

        //Definimos e executamos consulta para saber cantos rexistros van por páxina
        $stmt = $pdo->query('SELECT COUNT(*) FROM country');
        $totalRexistros = $stmt->fetchColumn();

        $totalPaxinas = ceil($totalRexistros / $numRexistrosPax);

        $filtro = array();
        if ($con == '' && $reg == '' && $nom == '') {
            $sql = 'SELECT Code, Name, Continent, Region FROM country LIMIT ' . (($paxina -1 ) * $numRexistrosPax) . ' , ' . $numRexistrosPax;
        } else {
            if ($con != '') {
                $array[] = $con;
            }
            if ($reg != '') {
                $array[] = $reg;
            }
            if ($nom != '') {
                $array[] = $nom;
            }
            $filtro = implode(', ', $array);
            $sql = 'SELECT Code, Name, Continent, Region FROM country ORDER BY ' . $filtro . ' LIMIT ' . (($paxina -1 ) * $numRexistrosPax) 
                . ' , ' . $numRexistrosPax;
        }

        $stmt = $pdo->query($sql);


        while ($row = $stmt->fetch()) {
            $senResultados = false;
            print<<<HTML
              <tr>
              <td>$row[Code]</td>
              <td>$row[Name]</td>
              <td>$row[Continent]</td>
              <td>$row[Region]</td>
              </tr>
HTML;
        }
        print<<<HTML
        </table></br>
        <div class="center"> 
            <span>Paxina actual:$paxina</span><br>
HTML;

        for ($i = 0; $i < $totalPaxinas; $i++) {
            echo '<a href="script10.php?pax=' . ($i + 1) . '">' . ($i + 1) . '</a> | ';
        }
        print<<<HTML
        </div>
HTML;


        if ($senResultados)
            print<<<HTML
                <tr><td>Non hai resultados</td></tr>
HTML;
        ?>

    </body>
</html>
