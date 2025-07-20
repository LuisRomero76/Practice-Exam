<?php

/** @var yii\web\View $this */

$this->title = 'Pokédex API - Yii2';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">¡Pokédex API & Exámenes!</h1>

        <p class="lead">Explora el mundo Pokémon y practica con nuestro sistema de exámenes de Auditoría de Sistemas.</p>

        <p>
            <a class="btn btn-lg btn-success me-2" href="<?= \yii\helpers\Url::to(['pokemon/index']) ?>">Ver Pokédex</a>
            <a class="btn btn-lg btn-primary me-2" href="<?= \yii\helpers\Url::to(['pokemon/search']) ?>">Buscar Pokémon</a>
            <a class="btn btn-lg btn-warning" href="<?= \yii\helpers\Url::to(['examen/index']) ?>">Tomar Examen</a>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Pokédex</h2>

                <p>Explora una extensa lista de Pokémon con información detallada incluyendo estadísticas, tipos, habilidades y más. Nuestra aplicación consume la API de PokéAPI para brindarte datos actualizados.</p>

                <p><a class="btn btn-outline-secondary" href="<?= \yii\helpers\Url::to(['pokemon/index']) ?>">Ver Pokédex &raquo;</a></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Buscar Pokémon</h2>

                <p>Encuentra tu Pokémon favorito rápidamente usando su nombre o ID. Obtén información completa con imágenes, estadísticas y características especiales de cada Pokémon.</p>

                <p><a class="btn btn-outline-secondary" href="<?= \yii\helpers\Url::to(['pokemon/search']) ?>">Buscar Pokémon &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Sistema de Exámenes</h2>

                <p>Pon a prueba tus conocimientos de Auditoría de Sistemas con nuestro sistema de exámenes interactivo. Incluye diferentes tipos de preguntas y retroalimentación detallada.</p>

                <p><a class="btn btn-outline-secondary" href="<?= \yii\helpers\Url::to(['examen/index']) ?>">Tomar Examen &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
