<?php  session_start(); ?>
<!DOCTYPE html>
<!--

- Ordenar os elementos segun Continente Rexion e Nome... 

Usaremos 3 checkbox

-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Consulta con parámetros</title>
        <link href="estilo.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <form style="text-align: center;" method='POST' action='<?php echo htmlentities($_SERVER['SCRIPT_NAME']); ?>'>
            <h2>Ordena os Paises según prefiras</h2>
            <input type="checkbox" name="continente" value="Continent" 
            <?php
            if (filter_input(INPUT_POST, 'continente') != '') {
                echo 'checked';
            }
            ?> > Continente
            <input type="checkbox" name="region" value="Region" <?php
            if (filter_input(INPUT_POST, 'region') != '') {
                echo 'checked';
            }
            ?>> Region
            <input type="checkbox" name="nome" value="Name" <?php
            if (filter_input(INPUT_POST, 'nome') != '') {
                echo 'checked';
            }
            ?> > Nome
            <input style="margin-left: 15px;" type='submit' value='Dalle!' name="filtra">
            <br>
            <br>
            <br>
        </form>  

        <?php
        print<<<HTML
                           
        <table  style='border-collapse: collapse; margin:auto;'>              
            <th style='width:20%;'>Code</th>
            <th style='width:20%;'>Name</th>
            <th style='width:20%;'>Continent</th>
            <th style='width:20%;'>Region</th>
            
HTML;
        /* Incluimos base de datos */
       
        require_once 'conexion.php';

        $numRexistrosPax = 25;
        $paxina = 1;
       
        //No caso de que esteamos noutra paxina diferente actualizamos valor $paxina
        if (array_key_exists('pax', $_GET)) {
            $paxina = $_GET['pax'];
        }

        //Executase cando lle damos o boton filtra
        if (array_key_exists('filtra', $_POST)) {
          $paxina = 1; //colocamonos na primeria paxina
         $_SESSION['cont'] = $_POST['continente'];
         $_SESSION['reg'] = $_POST['region'];
         $_SESSION['nom'] = $_POST['nome'];
           
            var_dump($_SESSION);
          }
        //Definimos e executamos consulta para saber cantos rexistros van por páxina
        $stmt1 = $pdo->query('SELECT COUNT(*) FROM country ');


        $totalRexistros = $stmt1->fetchColumn();

        $totalPaxinas = ceil($totalRexistros / $numRexistrosPax);


        //definimos consulta para ver os rexistros da BD
        $sql = 'SELECT Code, Name, Continent, Region FROM country '
                . 'LIMIT ' . (($paxina - 1) * $numRexistrosPax) . ', ' . $numRexistrosPax . ' ';

        /*         * ***** Aqui ordena ***************** */
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //saneamos cada opcion e poñemola como string
            $_SESSION['cont'] = $con = filter_input(INPUT_POST, 'continente', FILTER_SANITIZE_STRING);
            $_SESSION['reg'] = $reg = filter_input(INPUT_POST, 'region', FILTER_SANITIZE_STRING);
            $_SESSION['nom'] = $nom = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            //creamos un array para gardar os datos
            $filtro = array();
            //miramos se os campos estan checkeados ou non e procedemos co sql
            if ($con == '' && $reg == '' && $nom == '') {
                $sql = 'SELECT Code, Name, Continent, Region FROM country LIMIT ' . (($paxina - 1) * $numRexistrosPax) . ', ' . $numRexistrosPax;
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
                //unimos os elementos do array nun STRING (implode()) separandoos por comas.
                $filtro = implode(', ', $array);
                $sql = 'SELECT Code, Name, Continent, Region FROM country ORDER BY ' . $filtro . ' LIMIT ' . (($paxina - 1) * $numRexistrosPax) . ', ' . $numRexistrosPax;
            }
        }
        $stmt = $pdo->query($sql);
        /*         * *************************** */
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
        $pdo = null;

        echo "</table></br></br><div style='text-align: center;' id='paxinado'>";
        for ($i = 0; $i < $totalPaxinas; $i++) {
            echo '<a href="ordenar.php?pax=' . ($i + 1) . '">' . ($i + 1) . '</a> | ';
        }
        echo "</div>";
        ?>
    </body>
</html>
