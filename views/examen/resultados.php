<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $resultados */

$this->title = 'Resultados del Examen';
$this->params['breadcrumbs'][] = ['label' => 'Examen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Determinar el nivel de rendimiento
$porcentaje = $resultados['porcentaje'];
if ($porcentaje >= 90) {
    $nivel = ['Excelente', 'success', 'fas fa-trophy'];
} elseif ($porcentaje >= 80) {
    $nivel = ['Muy Bueno', 'primary', 'fas fa-medal'];
} elseif ($porcentaje >= 70) {
    $nivel = ['Bueno', 'info', 'fas fa-thumbs-up'];
} elseif ($porcentaje >= 60) {
    $nivel = ['Regular', 'warning', 'fas fa-hand-paper'];
} else {
    $nivel = ['Necesita Mejorar', 'danger', 'fas fa-book'];
}

$tiempoMinutos = floor($resultados['tiempo_total'] / 60);
$tiempoSegundos = $resultados['tiempo_total'] % 60;
?>

<div class="examen-resultados">
    <!-- Resumen de resultados -->
    <div class="row mb-4">
        <div class="col-md-8 offset-md-2">
            <div class="card border-<?= $nivel[1] ?>">
                <div class="card-header bg-<?= $nivel[1] ?> text-white text-center">
                    <h1><i class="<?= $nivel[2] ?>"></i> ¡Examen Completado!</h1>
                    <h2><?= $nivel[0] ?></h2>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <h3 class="display-4 text-<?= $nivel[1] ?>"><?= $porcentaje ?>%</h3>
                                <p class="text-muted">Puntuación</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <h3 class="display-4 text-success"><?= $resultados['correctas'] ?></h3>
                                <p class="text-muted">Correctas</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <h3 class="display-4 text-danger"><?= $resultados['incorrectas'] ?></h3>
                                <p class="text-muted">Incorrectas</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <h3 class="display-4 text-info"><?= $tiempoMinutos ?>:<?= sprintf('%02d', $tiempoSegundos) ?></h3>
                                <p class="text-muted">Tiempo</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barra de progreso visual -->
                    <div class="mt-4">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" style="width: <?= ($resultados['correctas'] / $resultados['total_preguntas']) * 100 ?>%">
                                <?= $resultados['correctas'] ?> Correctas
                            </div>
                            <div class="progress-bar bg-danger" style="width: <?= ($resultados['incorrectas'] / $resultados['total_preguntas']) * 100 ?>%">
                                <?= $resultados['incorrectas'] ?> Incorrectas
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <div class="d-grid gap-2 d-md-block">
                        <?= Html::a('Intentar de Nuevo', ['examen/index'], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Ver Pokédex', ['pokemon/index'], ['class' => 'btn btn-outline-secondary']) ?>
                        <?= Html::a('Descargar Resultados', '#', ['class' => 'btn btn-outline-info', 'onclick' => 'window.print(); return false;']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis por categorías -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Análisis por Categorías</h3>
                </div>
                <div class="card-body">
                    <?php 
                    $categorias = [];
                    foreach ($resultados['resultados'] as $resultado) {
                        $cat = $resultado['pregunta']['categoria'];
                        if (!isset($categorias[$cat])) {
                            $categorias[$cat] = ['total' => 0, 'correctas' => 0];
                        }
                        $categorias[$cat]['total']++;
                        if ($resultado['correcta']) {
                            $categorias[$cat]['correctas']++;
                        }
                    }
                    ?>
                    <div class="row">
                        <?php foreach ($categorias as $categoria => $stats): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5><?= $categoria ?></h5>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" style="width: <?= ($stats['correctas'] / $stats['total']) * 100 ?>%"></div>
                                        </div>
                                        <p class="mb-0"><?= $stats['correctas'] ?> de <?= $stats['total'] ?> correctas</p>
                                        <small class="text-muted"><?= round(($stats['correctas'] / $stats['total']) * 100, 1) ?>%</small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revisión detallada de preguntas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Revisión Detallada</h3>
                    <small class="text-muted">Revisa todas tus respuestas y las explicaciones</small>
                </div>
                <div class="card-body">
                    <?php foreach ($resultados['resultados'] as $index => $resultado): ?>
                        <div class="card mb-3 border-<?= $resultado['correcta'] ? 'success' : 'danger' ?>">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        Pregunta <?= $index + 1 ?>
                                        <?php if ($resultado['correcta']): ?>
                                            <span class="badge bg-success">Correcta</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Incorrecta</span>
                                        <?php endif; ?>
                                    </h5>
                                    <span class="badge bg-secondary"><?= $resultado['pregunta']['categoria'] ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="question-text"><?= Html::encode($resultado['pregunta']['pregunta']) ?></h6>
                                
                                <?php if ($resultado['pregunta']['tipo'] === 'relacionar'): ?>
                                    <!-- Para preguntas tipo RELACIONAR (con selects) -->
                                    <div class="mt-4">
                                        <div class="question-subtitle">Relacione correctamente:</div>
                                        <div class="relacionar-container">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th style="width: 35%;">Concepto</th>
                                                            <th style="width: 32.5%;">Tu Selección</th>
                                                            <th style="width: 32.5%;">Respuesta Correcta</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $respuestaUsuario = $resultado['respuesta_usuario'] ?: [];
                                                        $respuestaCorrecta = $resultado['respuesta_correcta'] ?: [];
                                                        
                                                        foreach ($resultado['pregunta']['conceptos'] as $concepto => $descripcion):
                                                            $seleccionUsuario = $respuestaUsuario[$concepto] ?? '';
                                                            $seleccionCorrecta = $respuestaCorrecta[$concepto] ?? '';
                                                            $esCorrecta = ($seleccionUsuario === $seleccionCorrecta);
                                                            
                                                            // Obtener el texto completo de las opciones
                                                            $textoSeleccionUsuario = '';
                                                            $textoSeleccionCorrecta = '';
                                                            
                                                            if (!empty($seleccionUsuario) && isset($resultado['pregunta']['opciones_select'][$seleccionUsuario])) {
                                                                $textoSeleccionUsuario = $resultado['pregunta']['opciones_select'][$seleccionUsuario];
                                                            } elseif (empty($seleccionUsuario)) {
                                                                $textoSeleccionUsuario = 'No seleccionaste nada';
                                                            }
                                                            
                                                            if (!empty($seleccionCorrecta) && isset($resultado['pregunta']['opciones_select'][$seleccionCorrecta])) {
                                                                $textoSeleccionCorrecta = $resultado['pregunta']['opciones_select'][$seleccionCorrecta];
                                                            }
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <strong><?= Html::encode($descripcion) ?></strong>
                                                                </td>
                                                                <td class="<?= $esCorrecta ? 'table-success' : 'table-danger' ?>">
                                                                    <?php if ($esCorrecta): ?>
                                                                        <i class="fas fa-check-circle text-success"></i>
                                                                        <strong>CORRECTO:</strong>
                                                                    <?php else: ?>
                                                                        <i class="fas fa-times-circle text-danger"></i>
                                                                        <strong>INCORRECTO:</strong>
                                                                    <?php endif; ?>
                                                                    <br>
                                                                    <span class="small"><?= Html::encode($textoSeleccionUsuario) ?></span>
                                                                </td>
                                                                <td class="table-success">
                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                    <strong>CORRECTO:</strong>
                                                                    <br>
                                                                    <span class="small"><?= Html::encode($textoSeleccionCorrecta) ?></span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <?php elseif ($resultado['pregunta']['tipo'] === 'select'): ?>
                                    <!-- Para preguntas tipo SELECT - 2 columnas -->
                                    <div class="mt-4">
                                        <div class="question-subtitle">Seleccione una opción:</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="select-column">
                                                    <h6 class="column-title">
                                                        <i class="fas fa-user"></i> Tu Selección:
                                                    </h6>
                                                    <div class="answer-display <?= $resultado['correcta'] ? 'correct-selection' : 'incorrect-selection' ?>">
                                                        <?php if ($resultado['correcta']): ?>
                                                            <i class="fas fa-check-circle text-success"></i>
                                                            <span class="ms-2"><strong>CORRECTO:</strong> <?= Html::encode($resultado['respuesta_usuario'] ?: 'No seleccionaste nada') ?></span>
                                                        <?php else: ?>
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                            <span class="ms-2"><strong>INCORRECTO:</strong> <?= Html::encode($resultado['respuesta_usuario'] ?: 'No seleccionaste nada') ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="select-column">
                                                    <h6 class="column-title">
                                                        <i class="fas fa-check"></i> Respuesta Correcta:
                                                    </h6>
                                                    <div class="answer-display correct-selection">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                        <span class="ms-2"><strong>CORRECTO:</strong> <?= Html::encode($resultado['respuesta_correcta']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <?php elseif (isset($resultado['pregunta']['opciones']) && !empty($resultado['pregunta']['opciones'])): ?>
                                    <!-- Para preguntas de opciones múltiples -->
                                    <div class="mt-4">
                                        <div class="question-subtitle">Seleccione una o más de una:</div>
                                        <div class="options-list">
                                            <?php 
                                            $respuestaUsuario = $resultado['respuesta_usuario'];
                                            $respuestaCorrecta = $resultado['respuesta_correcta'];
                                            
                                            // Convertir a arrays si no lo son
                                            $respuestasUsuario = is_array($respuestaUsuario) ? $respuestaUsuario : (empty($respuestaUsuario) ? [] : [$respuestaUsuario]);
                                            $respuestasCorrectas = is_array($respuestaCorrecta) ? $respuestaCorrecta : [$respuestaCorrecta];
                                            
                                            foreach ($resultado['pregunta']['opciones'] as $opcionKey => $opcionTexto):
                                                $seleccionadaPorUsuario = in_array($opcionKey, $respuestasUsuario);
                                                $esCorrecta = in_array($opcionKey, $respuestasCorrectas);
                                                
                                                // Determinar el estado visual
                                                if ($seleccionadaPorUsuario && $esCorrecta) {
                                                    $claseOpcion = 'option-perfect';
                                                    $iconoCheckbox = 'fas fa-check-square text-success';
                                                    $iconoEstado = 'fas fa-check text-success';
                                                    $textoEstado = 'CORRECTO - Seleccionaste bien';
                                                } elseif ($seleccionadaPorUsuario && !$esCorrecta) {
                                                    $claseOpcion = 'option-wrong';
                                                    $iconoCheckbox = 'fas fa-check-square text-danger';
                                                    $iconoEstado = 'fas fa-times text-danger';
                                                    $textoEstado = 'INCORRECTO - No debías seleccionarla';
                                                } elseif (!$seleccionadaPorUsuario && $esCorrecta) {
                                                    $claseOpcion = 'option-missed';
                                                    $iconoCheckbox = 'far fa-square text-muted';
                                                    $iconoEstado = 'fas fa-exclamation-triangle text-warning';
                                                    $textoEstado = 'TE FALTÓ SELECCIONAR - Era correcta';
                                                } else {
                                                    $claseOpcion = 'option-neutral';
                                                    $iconoCheckbox = 'far fa-square text-muted';
                                                    $iconoEstado = 'fas fa-check text-muted';
                                                    $textoEstado = 'Correcto al no seleccionarla';
                                                }
                                            ?>
                                                <div class="option-row <?= $claseOpcion ?>">
                                                    <div class="option-checkbox">
                                                        <i class="<?= $iconoCheckbox ?>"></i>
                                                    </div>
                                                    <div class="option-content">
                                                        <div class="option-text">
                                                            <strong><?= strtolower($opcionKey) ?>.</strong> <?= Html::encode($opcionTexto) ?>
                                                        </div>
                                                        <div class="option-status">
                                                            <i class="<?= $iconoEstado ?>"></i>
                                                            <?= $textoEstado ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <!-- Resumen de respuestas correctas -->
                                        <div class="mt-3 correct-answers-summary">
                                            <h6><i class="fas fa-list-check text-success"></i> Respuestas Correctas:</h6>
                                            <div class="correct-summary">
                                                <?php foreach ($respuestasCorrectas as $correcta): ?>
                                                    <span class="badge bg-success me-1">
                                                        <?= strtoupper($correcta) ?>. <?= Html::encode($resultado['pregunta']['opciones'][$correcta]) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <?php elseif ($resultado['pregunta']['tipo'] === 'completar'): ?>
                                    <!-- Para preguntas de completar texto -->
                                    <div class="mt-4">
                                        <div class="question-subtitle">Completar texto:</div>
                                        <div class="complete-answer-container">
                                            <div class="answer-box <?= $resultado['correcta'] ? 'correct' : 'incorrect' ?>">
                                                <?php if ($resultado['correcta']): ?>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                <?php endif; ?>
                                                <span class="ms-2">Tu respuesta: <strong><?= Html::encode($resultado['respuesta_usuario'] ?: 'Sin respuesta') ?></strong></span>
                                            </div>
                                            <?php if (!$resultado['correcta']): ?>
                                                <div class="answer-box correct">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    <span class="ms-2">Respuesta correcta: <strong><?= Html::encode($resultado['respuesta_correcta']) ?></strong></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Resumen de la respuesta -->
                                <div class="mt-3 p-2 border rounded">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted"><strong>Tipo de pregunta:</strong></small><br>
                                            <span class="badge bg-secondary"><?= ucwords(str_replace('_', ' ', $resultado['pregunta']['tipo'])) ?></span>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted"><strong>Tu resultado:</strong></small><br>
                                            <?php if ($resultado['correcta']): ?>
                                                <span class="badge bg-success">✅ Correcta</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">❌ Incorrecta</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted"><strong>Categoría:</strong></small><br>
                                            <span class="badge bg-info"><?= $resultado['pregunta']['categoria'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-box {
    padding: 15px;
    border-radius: 8px;
    margin: 10px 0;
}

.bg-success-light {
    background-color: #d4edda !important;
}

.bg-info-light {
    background-color: #d1ecf1 !important;
}

/* Diseño similar a las imágenes */
.question-text {
    font-size: 1.1em;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    border-radius: 0.25rem;
}

.question-subtitle {
    font-size: 1rem;
    color: #5a6c7d;
    margin-bottom: 1rem;
    font-weight: 500;
}

/* Estilos para opciones múltiples - como en las imágenes */
.options-list {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
}

.option-row {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
    padding: 0.5rem;
    background: white;
    border-radius: 0.25rem;
    border: 1px solid #e9ecef;
}

.option-row:last-child {
    margin-bottom: 0;
}

.option-checkbox {
    margin-right: 0.75rem;
    font-size: 1.2em;
}

.option-label {
    flex: 1;
    font-size: 0.95em;
    color: #495057;
}

/* Estilos para preguntas RELACIONAR */
.relacionar-container {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
}

.relacionar-container .table {
    margin-bottom: 0;
    background: white;
}

.relacionar-container .table th {
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}

.relacionar-container .table td {
    vertical-align: middle;
    padding: 1rem;
}

.table-success {
    background-color: #f8fff9 !important;
    border-color: #c3e6cb !important;
}

.table-danger {
    background-color: #fff8f8 !important;
    border-color: #f5c6cb !important;
}

/* Estilos para preguntas SELECT - 2 columnas */
.select-column {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    height: 100%;
}

.column-title {
    font-size: 0.9em;
    color: #6c757d;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    font-weight: 600;
}

.option-display {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 2px solid;
    background: white;
}

.correct-selection {
    border-color: #28a745;
    background-color: #f8fff9;
}

.incorrect-selection {
    border-color: #dc3545;
    background-color: #fff8f8;
}

/* Estilos mejorados para opciones múltiples */
.options-list {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
}

.option-row {
    display: flex;
    align-items: flex-start;
    margin-bottom: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.25rem;
    border: 1px solid #e9ecef;
    background: white;
}

.option-row:last-child {
    margin-bottom: 0;
}

/* Estados visuales mejorados */
.option-perfect {
    border-color: #28a745;
    background-color: #f8fff9;
}

.option-wrong {
    border-color: #dc3545;
    background-color: #fff8f8;
}

.option-missed {
    border-color: #ffc107;
    background-color: #fffbf0;
}

.option-neutral {
    border-color: #e9ecef;
    background-color: #ffffff;
}

.option-checkbox {
    margin-right: 0.75rem;
    font-size: 1.2em;
    min-width: 25px;
}

.option-content {
    flex: 1;
}

.option-text {
    font-size: 0.95em;
    color: #495057;
    margin-bottom: 0.25rem;
}

.option-status {
    font-size: 0.8em;
    font-weight: 600;
    opacity: 0.9;
}

/* Resumen de respuestas correctas */
.correct-answers-summary {
    background: #e8f5e8;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #c3e6cb;
}

.correct-summary {
    margin-top: 0.5rem;
}

.correct-summary .badge {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
}

/* Estilos para completar */
.complete-answer-container {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
}

.answer-box {
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 2px solid;
    margin-bottom: 0.5rem;
    background: white;
}

.answer-box.correct {
    border-color: #28a745;
    background-color: #f8fff9;
}

.answer-box.incorrect {
    border-color: #dc3545;
    background-color: #fff8f8;
}

/* Colores para iconos */
.text-success {
    color: #28a745 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.text-muted {
    color: #6c757d !important;
}

@media print {
    .card-footer, .btn {
        display: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        break-inside: avoid;
    }
}

.progress {
    border-radius: 10px;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
