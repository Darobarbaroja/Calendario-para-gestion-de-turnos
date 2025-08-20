<?php 
// Datos de turnos
$turnos = [
    '2025-08-25' => [['hora' => '12:50', 'nombre' => 'Juan Pérez']],
    '2025-08-27' => [['hora' => '18:30', 'nombre' => 'María González']],
    '2025-09-02' => [
        ['hora' => '17:00', 'nombre' => 'Laura Díaz'],
        ['hora' => '18:20', 'nombre' => 'Isabel Castillo']
    ]
];

// Función para mostrar turnos de un día
function get_turnos_dia($fecha, $turnos) {
    return isset($turnos[$fecha]) ? $turnos[$fecha] : [];
}

// Obtener mes y año desde GET, si no, usar actual
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date("n");
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date("Y");

// Día de hoy
$hoy = date("Y-m-d");

// Primer día del mes
$primer_dia_mes = mktime(0, 0, 0, $mes, 1, $anio);
$dia_semana_inicio = date("N", $primer_dia_mes); // 1 (Lun) - 7 (Dom)
$dias_mes = date("t", $primer_dia_mes); // cantidad de días

// Día seleccionado
$dia_seleccionado = isset($_GET['dia']) ? $_GET['dia'] : null;
$turnos_del_dia = $dia_seleccionado ? get_turnos_dia($dia_seleccionado, $turnos) : [];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario de Turnos</title>
    <link rel="stylesheet" href="calendario.css">
    <style>
        table { border-collapse: collapse; width: 100%; text-align: center; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        button { padding: 5px 10px; }
        .hoy { background: yellow; }
        .deshabilitado { color: #aaa; }
    </style>
</head>
<body>

    <h1>Calendario de Turnos - <?= strftime("%B %Y", $primer_dia_mes) ?></h1>

    <!-- Navegación de meses -->
    <div>
        <a href="?mes=<?= $mes == 1 ? 12 : $mes-1 ?>&anio=<?= $mes == 1 ? $anio-1 : $anio ?>">&#8592; Mes anterior</a> | 
        <a href="?mes=<?= $mes == 12 ? 1 : $mes+1 ?>&anio=<?= $mes == 12 ? $anio+1 : $anio ?>">Mes siguiente &#8594;</a>
    </div>

    <!-- Calendario -->
    <table>
        <thead>
            <tr>
                <th>Lun</th>
                <th>Mar</th>
                <th>Mié</th>
                <th>Jue</th>
                <th>Vie</th>
                <th>Sáb</th>
                <th>Dom</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <?php
            // Espacios vacíos hasta el primer día
            for ($i=1; $i<$dia_semana_inicio; $i++) {
                echo "<td></td>";
            }

            // Imprimir días
            for ($dia=1; $dia <= $dias_mes; $dia++) {
                $fecha_actual = sprintf("%04d-%02d-%02d", $anio, $mes, $dia);

                // Día de hoy
                $clase = ($fecha_actual == $hoy) ? "hoy" : "";

                echo "<td class='$clase'>";
                if ($fecha_actual > $hoy) {
                    echo "<button onclick=\"window.location.href='?mes=$mes&anio=$anio&dia=$fecha_actual'\">$dia</button>";
                } elseif ($fecha_actual == $hoy) {
                    echo "<span class='hoy'>$dia</span>";
                } else {
                    echo "<span class='deshabilitado'>$dia</span>";
                }
                echo "</td>";

                // Cambio de fila cada domingo
                if ((($dia + $dia_semana_inicio -1) % 7) == 0) {
                    echo "</tr><tr>";
                }
            }
            ?>
            </tr>
        </tbody>
    </table>

    <!-- Turnos -->
    <?php if ($dia_seleccionado): ?>
        <h2>Turnos para el <?= date("d/m/Y", strtotime($dia_seleccionado)) ?></h2>
        <ul>
            <?php if (count($turnos_del_dia) > 0): ?>
                <?php foreach ($turnos_del_dia as $turno): ?>
                    <li><?= $turno['hora'] ?> - <?= $turno['nombre'] ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No hay turnos asignados.</li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

</body>
</html>
