<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $preguntas */
/** @var array $categorias */
/** @var int $totalPreguntas */

$this->title = 'Examen de Auditoría de Sistemas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="examen-index">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0 text-center">
                        <i class="fas fa-clipboard-list"></i>
                        Sistema de Exámenes
                    </h1>
                    <p class="text-center mb-0">Auditoría de Sistemas</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Información del Examen</h3>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total de preguntas:</strong>
                                    <span class="badge bg-primary"><?= $totalPreguntas ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Categorías:</strong>
                                    <span class="badge bg-info"><?= count($categorias) ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Tiempo estimado:</strong>
                                    <span class="badge bg-warning"><?= $totalPreguntas * 2 ?> min</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Puntuación mínima:</strong>
                                    <span class="badge bg-success">70%</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3>Categorías de Preguntas</h3>
                            <div class="mb-3">
                                <?php foreach ($categorias as $categoria): ?>
                                    <span class="badge bg-secondary me-1 mb-1"><?= $categoria ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <h4>Tipos de Preguntas</h4>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-dot-circle text-primary"></i> Selección única</li>
                                <li><i class="fas fa-check-square text-success"></i> Selección múltiple</li>
                                <li><i class="fas fa-toggle-on text-info"></i> Verdadero/Falso</li>
                                <li><i class="fas fa-edit text-warning"></i> Completar texto</li>
                                <li><i class="fas fa-list text-secondary"></i> Lista desplegable</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <h4>¿Estás listo para comenzar?</h4>
                        <p class="text-muted">Una vez que inicies el examen, podrás navegar entre las preguntas libremente.</p>
                        <div class="d-grid gap-2 d-md-block">
                            <?= Html::a('Comenzar Examen', ['examen/iniciar'], [
                                'class' => 'btn btn-success btn-lg me-2',
                                'onclick' => 'return confirm("¿Estás seguro de que deseas iniciar el examen?");'
                            ]) ?>
                            <?= Html::a('Ver Pokédex', ['pokemon/index'], [
                                'class' => 'btn btn-outline-primary'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Instrucciones detalladas -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Instrucciones</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Durante el examen:</h5>
                            <ul>
                                <li>Puedes navegar entre preguntas usando los botones de navegación</li>
                                <li>Tus respuestas se guardan automáticamente</li>
                                <li>Puedes cambiar tus respuestas en cualquier momento</li>
                                <li>El examen se guarda en tu sesión actual</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Tipos de respuesta:</h5>
                            <ul>
                                <li><strong>Selección única:</strong> Elige una sola opción</li>
                                <li><strong>Selección múltiple:</strong> Puedes elegir varias opciones</li>
                                <li><strong>Verdadero/Falso:</strong> Selecciona verdadero o falso</li>
                                <li><strong>Completar:</strong> Escribe la respuesta en el campo de texto</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.list-group-item {
    border: none;
    padding: 0.5rem 0;
}
</style>
