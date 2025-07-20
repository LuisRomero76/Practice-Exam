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
                                <h6><?= Html::encode($resultado['pregunta']['pregunta']) ?></h6>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <strong>Tu respuesta:</strong>
                                        <div class="p-2 bg-light rounded">
                                            <?php if (is_array($resultado['respuesta_usuario'])): ?>
                                                <?= implode(', ', array_map('strtoupper', $resultado['respuesta_usuario'])) ?>
                                            <?php else: ?>
                                                <?= Html::encode($resultado['respuesta_usuario'] ?: 'Sin respuesta') ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if (!$resultado['correcta']): ?>
                                        <div class="col-md-6">
                                            <strong>Respuesta correcta:</strong>
                                            <div class="p-2 bg-success-light rounded">
                                                <?php if (is_array($resultado['respuesta_correcta'])): ?>
                                                    <?= implode(', ', array_map('strtoupper', $resultado['respuesta_correcta'])) ?>
                                                <?php else: ?>
                                                    <?= Html::encode($resultado['respuesta_correcta']) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($resultado['explicacion'])): ?>
                                    <div class="mt-3">
                                        <strong>Explicación:</strong>
                                        <div class="p-2 bg-info-light rounded">
                                            <?= Html::encode($resultado['explicacion']) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
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
