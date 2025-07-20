<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $pokemonList */
/** @var int $currentPage */
/** @var int $limit */

$this->title = 'Pokédex - Lista de Pokémon';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pokemon-index">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
            <p class="lead">Explora el mundo Pokémon</p>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Buscar Pokémon</h5>
                    <?= Html::beginForm(['pokemon/search'], 'get', ['class' => 'd-flex']) ?>
                        <?= Html::textInput('q', '', [
                            'class' => 'form-control me-2', 
                            'placeholder' => 'Nombre del Pokémon...'
                        ]) ?>
                        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($pokemonList['results'])): ?>
            <?php foreach ($pokemonList['results'] as $pokemon): ?>
                <?php 
                    // Extraer ID de la URL
                    $urlParts = explode('/', rtrim($pokemon['url'], '/'));
                    $pokemonId = end($urlParts);
                ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 pokemon-card" style="transition: transform 0.2s;">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= Html::encode(ucfirst($pokemon['name'])) ?></h5>
                            <p class="text-muted">#<?= $pokemonId ?></p>
                            <?= Html::a('Ver Detalles', ['pokemon/view', 'id' => $pokemonId], [
                                'class' => 'btn btn-primary btn-sm'
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    <h4>No se pudieron cargar los Pokémon</h4>
                    <p>Por favor, verifica tu conexión a internet e intenta nuevamente.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Paginación -->
    <nav aria-label="Navegación de páginas">
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <?= Html::a('« Anterior', ['pokemon/index', 'page' => $currentPage - 1], [
                        'class' => 'page-link'
                    ]) ?>
                </li>
            <?php endif; ?>
            
            <?php for ($i = max(1, $currentPage - 2); $i <= $currentPage + 2; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <?= Html::a($i, ['pokemon/index', 'page' => $i], [
                        'class' => 'page-link'
                    ]) ?>
                </li>
            <?php endfor; ?>
            
            <li class="page-item">
                <?= Html::a('Siguiente »', ['pokemon/index', 'page' => $currentPage + 1], [
                    'class' => 'page-link'
                ]) ?>
            </li>
        </ul>
    </nav>
</div>

<style>
.pokemon-card:hover {
    transform: translateY(-5px);
    box-shadow: 20px 20px 20px rgba(0,0,0,0.1);
}
</style>
