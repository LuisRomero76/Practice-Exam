<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $query */
/** @var app\models\Pokemon|null $pokemon */
/** @var string|null $error */

$this->title = 'Buscar Pokémon';
$this->params['breadcrumbs'][] = ['label' => 'Pokémon', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pokemon-search">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>
            
            <!-- Formulario de búsqueda -->
            <div class="card mb-4">
                <div class="card-body">
                    <?= Html::beginForm(['pokemon/search'], 'get') ?>
                        <div class="input-group input-group-lg">
                            <?= Html::textInput('q', $query, [
                                'class' => 'form-control', 
                                'placeholder' => 'Escribe el nombre o ID del Pokémon...',
                                'autofocus' => true
                            ]) ?>
                            <div class="input-group-append">
                                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                        <small class="form-text text-muted mt-2">
                            Ejemplos: pikachu, charizard, 25, 150
                        </small>
                    <?= Html::endForm() ?>
                </div>
            </div>

            <!-- Resultados -->
            <?php if (!empty($query)): ?>
                <?php if ($pokemon): ?>
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="mb-0">¡Pokémon encontrado!</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <?php if ($pokemon->getFrontImage()): ?>
                                        <?= Html::img($pokemon->getFrontImage(), [
                                            'class' => 'img-fluid',
                                            'alt' => $pokemon->name,
                                            'style' => 'max-width: 150px;'
                                        ]) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-8">
                                    <h2><?= Html::encode($pokemon->name) ?> <span class="text-muted">#<?= $pokemon->id ?></span></h2>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <p><strong>Altura:</strong> <?= $pokemon->getHeightInMeters() ?> m</p>
                                            <p><strong>Peso:</strong> <?= $pokemon->getWeightInKg() ?> kg</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p><strong>Tipos:</strong> 
                                                <?php foreach ($pokemon->types as $type): ?>
                                                    <span class="badge bg-primary me-1">
                                                        <?= ucfirst($type['type']['name']) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </p>
                                            <p><strong>Experiencia Base:</strong> <?= $pokemon->base_experience ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <?= Html::a('Ver Detalles Completos', ['pokemon/view', 'id' => $pokemon->id], [
                                            'class' => 'btn btn-primary'
                                        ]) ?>
                                        <?= Html::a('Buscar Otro', ['pokemon/search'], [
                                            'class' => 'btn btn-outline-secondary'
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger">
                        <h4>No encontrado</h4>
                        <p><?= Html::encode($error) ?></p>
                        <hr>
                        <p class="mb-0">
                            <strong>Sugerencias:</strong>
                            <ul>
                                <li>Verifica que el nombre esté escrito correctamente</li>
                                <li>Intenta con el nombre en inglés (ejemplo: "pikachu" en lugar de "pikachú")</li>
                                <li>Usa el ID numérico del Pokémon</li>
                            </ul>
                        </p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Información de ayuda -->
                <div class="card">
                    <div class="card-body text-center">
                        <h4>¿Cómo buscar?</h4>
                        <p class="text-muted">Puedes buscar Pokémon de las siguientes formas:</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Por Nombre</h5>
                                    <p class="small">pikachu, charizard, bulbasaur</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Por ID</h5>
                                    <p class="small">1, 25, 150, 493</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3 mb-3">
                                    <h5>Acceso Rápido</h5>
                                    <?= Html::a('Ver Todos', ['pokemon/index'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
