<!DOCTYPE html>
 <?php
 //inicializar variables
        session_start();
        $senResultados = true;
        $numRexistrosPax = 25;
        $paxina = 2;
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
        
        $paxina = $_SESSION['pax'];
        $con = $_SESSION['con'];
        $reg = $_SESSION['reg'];
        $nom = $_SESSION['nom'];
?>
<!--
Aplicación que ordena países segundo varios posibles criterios:
- Continente
- Rexión
- Nome

https://stackoverflow.com/questions/17603907/order-by-enum-field-in-mysql

-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Ordenando países por diferentes criterios</title>
        <link href="estilo.css" rel="stylesheet" type="text/css">

    </head>
    <body>
        <h2>Ordenando países</h2>
        <form method='POST' action='<?php  echo htmlentities($_SERVER['PHP_SELF']); ?>'>
            <fieldset>
                <legend>Filtrado:</legend>
                <p><label for="continent">Continente:</label>
                    <input type="checkbox" name="checklistCriterios[]" value="continent" />
                    <label for="region">Rexión:</label>
                    <input type="checkbox" name="checklistCriterios[]" value="region" />
                    <label for="name">Nome:</label>
                    <input type="checkbox" name="checklistCriterios[]" value="name" />
                </p>
                <input name='filtrar' type='submit' value='Filtrar' />
        </form>
        <br>

        <?php
        /* Se POST e non hai ERROS: CONEXION COA BD, CONSULTA - EXECUCIÓN */
        /* CONEXIÓN COA BD. RECUPERAR E MOSTRAR RESULTADOS */
       	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
            require_once 'conexion.php';


            $numRexistrosPax = 25;
        	$paxina = 1;
        	
        	//No caso de que esteamos noutra paxina diferente actualizamos valor $paxina
        	if (array_key_exists('pax', $_GET)) {
            $paxina = $_GET['pax'];
        	}


        	$stmt1 = $pdo->query('SELECT COUNT(*) FROM country ');


       		$totalRexistros = $stmt1->fetchColumn();

        	$totalPaxinas = ceil($totalRexistros / $numRexistrosPax);


            $sql = 'SELECT * FROM country ';         	
        



            if (filter_has_var(INPUT_POST, 'checklistCriterios')) {
                $_SESSION['criterios'] = $_POST['checklistCriterios'];
               	/*
               	current() -----> Cada array tiene un puntero interno a su elemento "actual",
               	que es iniciado desde el primer elemento insertado en el array.
               	*/
                $criterio = current($_SESSION['criterios']);

                $sql .= ' ORDER BY '.$criterio.' LIMIT ' . (($paxina - 1) * $numRexistrosPax) . ' , ' . $numRexistrosPax . ' ';

                if (count($_SESSION['criterios']) > 1) {
                    foreach ($_SESSION['criterios'] as $criterio) {
                        $sql .= ", $criterio ";
                    }
                }
            }
            var_dump($_SESSION['criterios']);

            $stmt = $pdo->query($sql);

            $resultados = $stmt->fetchAll();
            ?>
            <table>
                <table>
                    <th>Código País</th>
                    <th>Nome do País</th>
                    <th>Continente</th>
                    <th>Rexión</th>
                    <?php
                    foreach ($resultados as $fila) {
                        print<<<HTML
                                    <tr>
                                        <td>$fila[Code]</td>
                                        <td>$fila[Name]</td>
                                        <td>$fila[Continent]</td>
                                        <td>$fila[Region]</td>
                                    </tr>
HTML;
                    }
              }

                
                 echo "</table></br></br><div style='text-align: center;' id='paxinado'>";
        		for ($i = 0; $i < $totalPaxinas; $i++) {
            	echo '<a href="infopaisesfiltrosorde.php?pax=' . ($i + 1) . '">' . ($i + 1) . '</a> | ';
        		}
        		echo "</div>";
                ?>
</body>
</html>
