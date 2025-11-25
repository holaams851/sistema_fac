<?php
// Función para mostrar una tabla HTML con contador automático
// $resultado = resultado de $conn->query($sql)
// $columnas = array con los nombres de las columnas que quieres mostrar ['id_cliente','nombre','telefono']
// $acciones = array opcional con botones ['editar'=>'editar_cliente.php','eliminar'=>'eliminar_cliente.php']

function mostrarTabla($resultado, $columnas, $acciones = []) {
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>#</th>"; //
    foreach ($columnas as $col) {
        echo "<th>".ucfirst(str_replace('_',' ',$col))."</th>";
    }
    if(!empty($acciones)) {
        echo "<th>Acciones</th>";
    }
    echo "</tr></thead><tbody>";

    $contador = 1;
    while($row = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$contador}</td>";
        foreach ($columnas as $col) {
            echo "<td>{$row[$col]}</td>";
        }
        if(!empty($acciones)) {
            echo "<td>";
            $cont_acciones = 0; // Contador para las acciones
            foreach($acciones as $nombre => $url) {
                $cont_acciones++;
                if($cont_acciones == 1) { // Primer boton color
                    $color = 'btn-warning'; // Cambia el color del primer botón a amarillo
                } else if($cont_acciones == 2) { // Segundo boton color
                    $color = 'btn-danger'; // Cambia el color del segundo botón a rojo
                } else { // Tercer boton color
                    $color = 'btn-success'; // Cambia el color del tercer botón a verde
                }
                echo "<a href='{$url}?id={$row[$columnas[0]]}' class='btn btn-sm $color me-1'>".ucfirst($nombre)."</a>";
            }
            echo "</td>";
        }
        echo "</tr>";
        $contador++;
    }

    echo "</tbody></table>";
}
