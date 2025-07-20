<?php

use yii\helpers\Html;
use app\models\Pregunta;

/** @var yii\web\View $this */
/** @var array $pregunta */
/** @var int $preguntaActual */
/** @var int $totalPreguntas */
/** @var mixed $respuestaGuardada */

$this->title = "Pregunta {$preguntaActual} de {$totalPreguntas}";
$this->params['breadcrumbs'][] = ['label' => 'Examen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="examen-pregunta">
    <!-- Barra de progreso -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pregunta <?= $preguntaActual ?> de <?= $totalPreguntas ?></h5>
                        <span class="badge bg-info"><?= $pregunta['categoria'] ?></span>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: <?= ($preguntaActual / $totalPreguntas) * 100 ?>%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Navegador de preguntas -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h6>Navegación</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php for ($i = 1; $i <= $totalPreguntas; $i++): ?>
                            <div class="col-4 mb-2">
                                <?= Html::a($i, ['examen/navegar', 'id' => $i], [
                                    'class' => 'btn btn-sm ' . ($i == $preguntaActual ? 'btn-primary' : 'btn-outline-secondary'),
                                    'style' => 'width: 100%;'
                                ]) ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="mt-3">
                        <?= Html::a('← Anterior', ['examen/navegar', 'id' => max(1, $preguntaActual - 1)], [
                            'class' => 'btn btn-secondary btn-sm me-2',
                            'style' => $preguntaActual <= 1 ? 'visibility: hidden;' : ''
                        ]) ?>
                        
                        <?= Html::a('Siguiente →', ['examen/navegar', 'id' => min($totalPreguntas, $preguntaActual + 1)], [
                            'class' => 'btn btn-primary btn-sm',
                            'style' => $preguntaActual >= $totalPreguntas ? 'visibility: hidden;' : ''
                        ]) ?>
                    </div>
                    
                    <?php if ($preguntaActual >= $totalPreguntas): ?>
                        <div class="mt-3">
                            <?= Html::a('Finalizar Examen', ['examen/finalizar'], [
                                'class' => 'btn btn-success btn-sm w-100',
                                'onclick' => 'return confirm("¿Está seguro de que desea finalizar el examen?")'
                            ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pregunta principal -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Pregunta <?= $preguntaActual ?></h4>
                    <small class="text-muted">Tipo: <?= ucwords(str_replace('_', ' ', $pregunta['tipo'])) ?></small>
                </div>
                <div class="card-body">
                    <h5 class="mb-4"><?= Html::encode($pregunta['pregunta']) ?></h5>
                    
                    <?= Html::beginForm(['examen/responder'], 'post', ['id' => 'form-pregunta']) ?>
                    
                    <?= Html::hiddenInput('pregunta_id', $pregunta['id']) ?>
                    <?= Html::hiddenInput('accion', '', ['id' => 'input-accion']) ?>
                    
                    <!-- Diferentes tipos de preguntas -->
                    <?php if ($pregunta['tipo'] === Pregunta::TIPO_RELACIONAR): ?>
                        <!-- Pregunta de tipo relacionar con selects -->
                        <?php
                        // Desordenar las opciones de manera consistente usando semilla
                        $semilla = crc32(Yii::$app->session->getId() . $pregunta['id']);
                        mt_srand($semilla);
                        
                        $opcionesDesordenadas = $pregunta['opciones_select'];
                        $opcionesParaDesordenar = $opcionesDesordenadas;
                        unset($opcionesParaDesordenar['']); // Mantener la opción vacía al inicio
                        
                        // Crear array con las claves y desordenar
                        $keys = array_keys($opcionesParaDesordenar);
                        shuffle($keys);
                        
                        $opcionesDesordenadas = ['' => 'Seleccione una opción...'];
                        foreach ($keys as $key) {
                            $opcionesDesordenadas[$key] = $pregunta['opciones_select'][$key];
                        }
                        
                        // Restaurar semilla aleatoria
                        mt_srand();
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50%">Concepto</th>
                                        <th width="50%">Relacionar con</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pregunta['conceptos'] as $key => $concepto): ?>
                                        <tr>
                                            <td class="align-middle">
                                                <strong><?= Html::encode($concepto) ?></strong>
                                            </td>
                                            <td>
                                                <?= Html::dropDownList(
                                                    "respuesta[{$key}]",
                                                    $respuestaGuardada[$key] ?? '',
                                                    $opcionesDesordenadas,
                                                    ['class' => 'form-select']
                                                ) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    <?php elseif ($pregunta['tipo'] === Pregunta::TIPO_SELECCION_UNICA): ?>
                        <!-- Pregunta de selección única -->
                        <?php
                        // Desordenar las opciones de manera consistente
                        $semilla = crc32(Yii::$app->session->getId() . $pregunta['id']);
                        mt_srand($semilla);
                        
                        $keys = array_keys($pregunta['opciones']);
                        shuffle($keys);
                        
                        mt_srand(); // Restaurar semilla aleatoria
                        ?>
                        <?php foreach ($keys as $key): ?>
                            <div class="form-check mb-2">
                                <?= Html::radio('respuesta', $respuestaGuardada == $key, [
                                    'value' => $key,
                                    'id' => "opcion_{$key}",
                                    'class' => 'form-check-input'
                                ]) ?>
                                <label class="form-check-label" for="opcion_<?= $key ?>">
                                    <strong><?= $key ?>)</strong> <?= Html::encode($pregunta['opciones'][$key]) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                    <?php elseif ($pregunta['tipo'] === Pregunta::TIPO_SELECCION_MULTIPLE): ?>
                        <!-- Pregunta de selección múltiple -->
                        <p class="text-muted mb-3"><em>Selecciona todas las opciones correctas:</em></p>
                        <?php
                        // Desordenar las opciones de manera consistente
                        $semilla = crc32(Yii::$app->session->getId() . $pregunta['id']);
                        mt_srand($semilla);
                        
                        $keys = array_keys($pregunta['opciones']);
                        shuffle($keys);
                        
                        mt_srand(); // Restaurar semilla aleatoria
                        ?>
                        <?php foreach ($keys as $key): ?>
                            <div class="form-check mb-2">
                                <?php 
                                $checked = is_array($respuestaGuardada) && in_array($key, $respuestaGuardada);
                                ?>
                                <?= Html::checkbox("respuesta[]", $checked, [
                                    'value' => $key,
                                    'id' => "opcion_{$key}",
                                    'class' => 'form-check-input'
                                ]) ?>
                                <label class="form-check-label" for="opcion_<?= $key ?>">
                                    <strong><?= $key ?>)</strong> <?= Html::encode($pregunta['opciones'][$key]) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                    <?php elseif ($pregunta['tipo'] === Pregunta::TIPO_VERDADERO_FALSO): ?>
                        <!-- Pregunta Verdadero/Falso -->
                        <?php foreach ($pregunta['opciones'] as $key => $opcion): ?>
                            <div class="form-check mb-2">
                                <?= Html::radio('respuesta', $respuestaGuardada == $key, [
                                    'value' => $key,
                                    'id' => "opcion_{$key}",
                                    'class' => 'form-check-input'
                                ]) ?>
                                <label class="form-check-label" for="opcion_<?= $key ?>">
                                    <?= Html::encode($opcion) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        
                    <?php elseif ($pregunta['tipo'] === Pregunta::TIPO_COMPLETAR): ?>
                        <!-- Pregunta para completar -->
                        <div class="mb-3">
                            <?= Html::textInput('respuesta', $respuestaGuardada, [
                                'class' => 'form-control',
                                'placeholder' => 'Escribe tu respuesta aquí...'
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?= Html::endForm() ?>
                </div>
                
                <!-- Botones de navegación -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php if ($preguntaActual > 1): ?>
                                <?= Html::button('« Anterior', [
                                    'class' => 'btn btn-outline-secondary',
                                    'onclick' => 'navegarPregunta("anterior")'
                                ]) ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="text-center">
                            <?= Html::button('Guardar Respuesta', [
                                'class' => 'btn btn-info',
                                'onclick' => 'guardarRespuesta()'
                            ]) ?>
                        </div>
                        
                        <div>
                            <?php if ($preguntaActual < $totalPreguntas): ?>
                                <?= Html::button('Siguiente »', [
                                    'class' => 'btn btn-primary',
                                    'onclick' => 'navegarPregunta("siguiente")'
                                ]) ?>
                            <?php else: ?>
                                <?= Html::button('Finalizar Examen', [
                                    'class' => 'btn btn-success',
                                    'onclick' => 'finalizarExamen()'
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function guardarRespuesta() {
    // Solo guardar la respuesta, no navegar
    document.getElementById('input-accion').value = 'guardar';
    document.getElementById('form-pregunta').submit();
}

function navegarPregunta(direccion) {
    // Primero guardar la respuesta actual, luego navegar
    document.getElementById('input-accion').value = direccion;
    document.getElementById('form-pregunta').submit();
}

function finalizarExamen() {
    if (confirm('¿Está seguro de que desea finalizar el examen? No podrá cambiar sus respuestas después.')) {
        document.getElementById('input-accion').value = 'finalizar';
        document.getElementById('form-pregunta').submit();
    }
}

// Auto-guardar cada 30 segundos (opcional)
setInterval(function() {
    // Verificar si hay cambios y guardar silenciosamente
    if (document.querySelector('input:checked, select option:checked, input[type="text"]:not([value=""])')) {
        var xhr = new XMLHttpRequest();
        var formData = new FormData(document.getElementById('form-pregunta'));
        formData.set('accion', 'auto_guardar');
        
        xhr.open('POST', document.getElementById('form-pregunta').action, true);
        xhr.send(formData);
    }
}, 30000);
</script>

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.form-check-label {
    cursor: pointer;
    padding-left: 0.5rem;
}

.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-group-navigation {
    display: flex;
    gap: 0.5rem;
}
</style>
