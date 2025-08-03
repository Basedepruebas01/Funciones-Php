<!-- Nombre del programador
Curso: Desarrollo Web con Php
Evidencia 2: Taller: “Uso de funciones” -->
<?php
session_start();
include("biblioteca.php");

// Borrar historial ANTES de imprimir HTML
if (isset($_POST['borrar_historial'])) {
    unset($_SESSION['historial']);
    header("Location: index.php");
    exit;
}

// Procesar cálculo ANTES del HTML
$js_resultado = '';
if (isset($_POST['calcular'])) {
    $n1 = $_POST['numero1'];
    $n2 = $_POST['numero2'];
    $op = $_POST['opciones'];

    switch ($op) {
        case 0:
            $operacion = "$n1 + $n2";
            $resultadoTexto = sumar($n1, $n2);
            break;
        case 1:
            $operacion = "$n1 - $n2";
            $resultadoTexto = restar($n1, $n2);
            break;
        case 2:
            $operacion = "$n1 * $n2";
            $resultadoTexto = multiplicar($n1, $n2);
            break;
        case 3:
            $operacion = "$n1 / $n2";
            $resultadoTexto = dividir($n1, $n2);
            break;
        default:
            $operacion = "Operación inválida";
            $resultadoTexto = "Error: operación no válida.";
    }

    // Guardar en historial
    $_SESSION['historial'][] = [
        'operacion' => $operacion,
        'resultado' => $resultadoTexto
    ];

    $alerta = (str_contains($resultadoTexto, 'Error') || str_contains($resultadoTexto, 'no se puede'))
        ? 'alert-danger'
        : 'alert-info';

    $js_resultado = json_encode([
        'texto' => "$operacion = $resultadoTexto",
        'alerta' => $alerta
    ]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operaciones Básicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @keyframes zoomIn {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .zoomIn {
            animation: zoomIn 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Operaciones Básicas</h2>

            <form id="formulario" method="post" action="index.php" novalidate>
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-5">
                        <input type="text" class="form-control" name="numero1" id="numero1" placeholder="Primer número" required>
                        <div class="invalid-feedback">Por favor ingresa un número válido.</div>
                    </div>

                    <div class="col-12 col-md-2">
                        <select name="opciones" class="form-select">
                            <option value="0">Sumar</option>
                            <option value="1">Restar</option>
                            <option value="2">Multiplicar</option>
                            <option value="3">Dividir</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-5">
                        <input type="text" class="form-control" name="numero2" id="numero2" placeholder="Segundo número" required>
                        <div class="invalid-feedback">Por favor ingresa un número válido.</div>
                    </div>
                </div>

                <div class="row g-2 justify-content-center">
                    <div class="col-12 col-sm-auto">
                        <input type="submit" name="calcular" class="btn btn-primary w-100" value="Calcular">
                    </div>
                    <div class="col-12 col-sm-auto">
                        <input type="reset" class="btn btn-secondary w-100" value="Borrar">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($_SESSION['historial'])): ?>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
            <span>Historial de operaciones</span>
            <form method="post" action="index.php" class="m-0">
                <input type="hidden" name="borrar_historial" value="1">
                <button type="submit" class="btn btn-sm btn-danger">Borrar Historial</button>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped m-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Operación</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['historial'] as $index => $registro): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($registro['operacion']) ?></td>
                            <td><?= htmlspecialchars($registro['resultado']) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal con animación -->
<div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="resultadoLabel">Resultado de la operación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <div id="resultadoIcono" class="mb-3" style="font-size: 3rem;"></div>
        <div id="resultadoContenido" class="fs-5 fw-semibold"></div>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const form = document.getElementById('formulario');

    form.addEventListener('submit', function (e) {
        let valid = true;
        const num1 = document.getElementById('numero1');
        const num2 = document.getElementById('numero2');

        if (num1.value.trim() === "" || isNaN(num1.value)) {
            num1.classList.add('is-invalid');
            valid = false;
        } else {
            num1.classList.remove('is-invalid');
            num1.classList.add('is-valid');
        }

        if (num2.value.trim() === "" || isNaN(num2.value)) {
            num2.classList.add('is-invalid');
            valid = false;
        } else {
            num2.classList.remove('is-invalid');
            num2.classList.add('is-valid');
        }

        if (!valid) {
            e.preventDefault();
        }
    });

    <?php if (!empty($js_resultado)): ?>
    document.addEventListener('DOMContentLoaded', function () {
        const resultado = <?= $js_resultado ?>;
        const modal = new bootstrap.Modal(document.getElementById('resultadoModal'));
        const resultadoDiv = document.getElementById('resultadoContenido');
        const iconoDiv = document.getElementById('resultadoIcono');

        resultadoDiv.className = 'fs-5 fw-semibold';
        resultadoDiv.innerHTML = resultado.texto;

        if (resultado.alerta === 'alert-info') {
            iconoDiv.innerHTML = '<span class="text-success zoomIn">✅</span>';
        } else {
            iconoDiv.innerHTML = '<span class="text-danger zoomIn">❌</span>';
        }

        modal.show();

        // Copiar automáticamente al portapapeles
        navigator.clipboard.writeText(resultado.texto).then(() => {
            console.log("Resultado copiado al portapapeles");
        });

        // Guardar en localStorage
        const historial = JSON.parse(localStorage.getItem('historial') || '[]');
        historial.push(resultado.texto);
        localStorage.setItem('historial', JSON.stringify(historial));

        // Reset formulario
        form.reset();
        ['numero1', 'numero2'].forEach(id => {
            const input = document.getElementById(id);
            input.classList.remove('is-valid', 'is-invalid');
        });

        document.getElementById('resultadoModal').addEventListener('hidden.bs.modal', () => {
            document.getElementById('numero1').focus();
        });
    });
    <?php endif; ?>
</script>

</body>
</html>
