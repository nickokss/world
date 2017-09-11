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
             <fieldset>
                <legend>Filtrado:</legend>
                <p><label for="continent">Continente:</label>
                    <input type="checkbox" name="checklistCriterios[]" value=" continent " />
                    <label for="region">Rexión:</label>
                    <input type="checkbox" name="checklistCriterios[]" value=" region " />
                    <label for="name">Nome:</label>
                    <input type="checkbox" name="checklistCriterios[]" value=" name "  />
                </p>
                <input name='filtrar' type='submit' value='Filtrar' />
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
            //Inicializamos variables para paxinacion
            $numRexistrosPax = 25;
            $paxina = 1;

            //No caso de que esteamos noutra paxina diferente actualizamos valor $paxina
            if (array_key_exists('pax', $_GET)) {
              $paxina = $_GET['pax'];
              $_SESSION['paxContas'] = $paxina;
            }


            //CONSULTA PARA PAXINAR
            //Definimos e executamos consulta para saber cantos rexistros van por páxina
                               
            $stmt1 = $pdo->query('SELECT COUNT(*) FROM country ');

            $totalRexistros = $stmt1->fetchColumn();

            $totalPaxinas = ceil($totalRexistros / $numRexistrosPax);


            $sql2 = "SELECT Code, Name, Continent, Region FROM country ";
           
              
             //Executase a primeira vez que entramos na páxina
            if (!array_key_exists('pax', $_GET)) {            
              $_SESSION['sens'] = " continent ";
            }


           
             
           if (array_key_exists('filtrar', $_POST)) {
              $paxina = 1; //colocamonos na primeria paxina
              $criterios = $_SESSION['sens'] = $_POST['checklistCriterios'];              
               
             }
               $criterio = current($criterios); 
                            
            $sql2 .= " ORDER BY "  . $criterio ." ";  
              
            $sql2 .= " LIMIT " . (($paxina - 1) * $numRexistrosPax) . " , " . $numRexistrosPax;


                  if (count($criterios) > 1) {
                    foreach ($criterios as $criterio) {
                        $sql2 .= " , $criterio ";
                    }
                }
 
             $stmt2 = $pdo->query($sql2);
        /*         * *************************** */
        while ($row = $stmt2->fetch()) {
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
            echo '<a href="ooorden.php?pax=' . ($i + 1) . '">' . ($i + 1) . '</a> | ';
        }
        echo "</div>";
        ?>
    </body>
</html>
