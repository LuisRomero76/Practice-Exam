<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Pokemon $pokemon */

$this->title = $pokemon->name . ' - Pokédex';
$this->params['breadcrumbs'][] = ['label' => 'Pokémon', 'url' => ['index']];
$this->params['breadcrumbs'][] = $pokemon->name;
?>

<div class="pokemon-view">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><?= Html::encode($pokemon->name) ?> <span class="text-muted">#<?= $pokemon->id ?></span></h1>
                <?= Html::a('« Volver a la lista', ['pokemon/index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Imagen del Pokémon -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($pokemon->getFrontImage()): ?>
                        <?= Html::img($pokemon->getFrontImage(), [
                            'class' => 'img-fluid',
                            'alt' => $pokemon->name,
                            'style' => 'max-width: 200px;'
                        ]) ?>
                    <?php else: ?>
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x"></i>
                            <p>Imagen no disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Información básica -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Información Básica</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Altura:</strong></td>
                                    <td><?= $pokemon->getHeightInMeters() ?> m</td>
                                </tr>
                                <tr>
                                    <td><strong>Peso:</strong></td>
                                    <td><?= $pokemon->getWeightInKg() ?> kg</td>
                                </tr>
                                <tr>
                                    <td><strong>Experiencia Base:</strong></td>
                                    <td><?= $pokemon->base_experience ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tipos:</strong></td>
                                    <td>
                                        <?php foreach ($pokemon->types as $type): ?>
                                            <span class="badge bg-primary me-1">
                                                <?= ucfirst($type['type']['name']) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Habilidades:</strong></td>
                                    <td>
                                        <?php foreach ($pokemon->abilities as $ability): ?>
                                            <span class="badge bg-secondary me-1">
                                                <?= ucfirst($ability['ability']['name']) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($pokemon->stats as $stat): ?>
                            <div class="col-md-4 mb-3">
                                <div class="stat-item">
                                    <label class="form-label">
                                        <strong><?= ucfirst(str_replace('-', ' ', $stat['stat']['name'])) ?>:</strong>
                                    </label>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: <?= min(100, ($stat['base_stat'] / 200) * 100) ?>%"
                                             aria-valuenow="<?= $stat['base_stat'] ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="200">
                                            <?= $stat['base_stat'] ?>
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

    <!-- Navegación entre Pokémon -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <?php if ($pokemon->id > 1): ?>
                    <?= Html::a('« Pokémon Anterior', ['pokemon/view', 'id' => $pokemon->id - 1], [
                        'class' => 'btn btn-outline-primary'
                    ]) ?>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
                
                <?= Html::a('Siguiente Pokémon »', ['pokemon/view', 'id' => $pokemon->id + 1], [
                    'class' => 'btn btn-outline-primary'
                ]) ?>
            </div>
        </div>
    </div>

    <!-- Datos JSON -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>API Endpoint</h5>
                </div>
                <div class="card-body">
                    <p>También puedes obtener estos datos en formato JSON:</p>
                    <code><?= Url::to(['pokemon/api', 'id' => $pokemon->id], true) ?></code>
                    <br><br>
                    <?= Html::a('Ver JSON', ['pokemon/api', 'id' => $pokemon->id], [
                        'class' => 'btn btn-sm btn-info',
                        'target' => '_blank'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-item {
    margin-bottom: 10px;
}
.progress-bar {
    font-weight: bold;
    line-height: 25px;
}
</style>
