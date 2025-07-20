<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $preguntas */

$this->title = 'Revisar Preguntas - Modo Estudio';
$this->params['breadcrumbs'][] = ['label' => 'Examen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="examen-revisar">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h1 class="display-4">📚 Modo Estudio</h1>
                    <p class="lead">Revisa todas las preguntas con sus respuestas correctas</p>
                    <p class="mb-0">Total de preguntas: <strong><?= count($preguntas) ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3>📝 Banco de Preguntas</h3>
                <?= Html::a('🚀 Hacer Examen', ['examen/index'], [
                    'class' => 'btn btn-success'
                ]) ?>
            </div>
        </div>
    </div>

    <?php foreach ($preguntas as $index => $pregunta): ?>
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <span class="badge bg-light text-dark me-2"><?= $pregunta['id'] ?></span>
                        Pregunta <?= $pregunta['id'] ?>
                    </h5>
                    <span class="badge bg-light text-dark">
                        <?= Html::encode($pregunta['categoria']) ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h6 class="card-subtitle mb-3 text-primary">
                    📋 <?= Html::encode($pregunta['pregunta']) ?>
                </h6>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6>Opciones:</h6>
                        
                        <?php if ($pregunta['tipo'] === 'seleccion_unica' || $pregunta['tipo'] === 'seleccion_multiple'): ?>
                            <?php foreach ($pregunta['opciones'] as $key => $opcion): ?>
                                <?php
                                $esCorrecta = false;
                                if ($pregunta['tipo'] === 'seleccion_multiple') {
                                    $esCorrecta = in_array($key, $pregunta['respuesta_correcta']);
                                } else {
                                    $esCorrecta = $key === $pregunta['respuesta_correcta'];
                                }
                                ?>
                                <div class="mb-2 p-2 <?= $esCorrecta ? 'bg-success text-white' : 'bg-light' ?> rounded">
                                    <?= $esCorrecta ? '✅' : '⭕' ?> 
                                    <strong><?= strtoupper($key) ?>)</strong> 
                                    <?= Html::encode($opcion) ?>
                                    <?= $esCorrecta ? ' <span class="badge bg-light text-success ms-2">CORRECTA</span>' : '' ?>
                                </div>
                            <?php endforeach; ?>
                            
                        <?php elseif ($pregunta['tipo'] === 'verdadero_falso'): ?>
                            <?php foreach ($pregunta['opciones'] as $key => $opcion): ?>
                                <div class="mb-2 p-2 <?= $key === $pregunta['respuesta_correcta'] ? 'bg-success text-white' : 'bg-light' ?> rounded">
                                    <?= $key === $pregunta['respuesta_correcta'] ? '✅' : '⭕' ?> 
                                    <strong><?= Html::encode($opcion) ?></strong>
                                    <?= $key === $pregunta['respuesta_correcta'] ? ' <span class="badge bg-light text-success ms-2">CORRECTA</span>' : '' ?>
                                </div>
                            <?php endforeach; ?>
                            
                        <?php elseif ($pregunta['tipo'] === 'completar'): ?>
                            <div class="mb-2 p-3 bg-success text-white rounded">
                                <strong>✅ Respuesta correcta:</strong> 
                                <code class="text-white"><?= Html::encode($pregunta['respuesta_correcta']) ?></code>
                            </div>
                            
                        <?php elseif ($pregunta['tipo'] === 'select'): ?>
                            <?php foreach ($pregunta['opciones'] as $key => $opcion): ?>
                                <div class="mb-2 p-2 <?= $key === $pregunta['respuesta_correcta'] ? 'bg-success text-white' : 'bg-light' ?> rounded">
                                    <?= $key === $pregunta['respuesta_correcta'] ? '✅' : '⭕' ?> 
                                    <strong><?= Html::encode($opcion) ?></strong>
                                    <?= $key === $pregunta['respuesta_correcta'] ? ' <span class="badge bg-light text-success ms-2">CORRECTA</span>' : '' ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="fas fa-check-circle"></i> Respuesta Correcta
                                </h6>
                                <p class="card-text">
                                    <?php if (is_array($pregunta['respuesta_correcta'])): ?>
                                        <strong>Opciones:</strong> <?= implode(', ', array_map('strtoupper', $pregunta['respuesta_correcta'])) ?>
                                    <?php else: ?>
                                        <strong>Opción:</strong> <?= strtoupper($pregunta['respuesta_correcta']) ?>
                                    <?php endif; ?>
                                </p>
                                
                                <small class="text-muted">
                                    <strong>Tipo:</strong> <?= ucwords(str_replace('_', ' ', $pregunta['tipo'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($pregunta['explicacion'])): ?>
                    <div class="mt-3 p-3 bg-info text-white rounded">
                        <h6><i class="fas fa-lightbulb"></i> Explicación:</h6>
                        <p class="mb-0"><?= Html::encode($pregunta['explicacion']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Navegación al final -->
    <div class="row mt-5">
        <div class="col-md-12 text-center">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4>🎯 ¿Listo para el Examen?</h4>
                    <p>Has revisado todas las <?= count($preguntas) ?> preguntas. ¡Ahora pon a prueba tus conocimientos!</p>
                    <?= Html::a('Hacer Examen Completo', ['examen/index'], [
                        'class' => 'btn btn-light btn-lg'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
</style>
