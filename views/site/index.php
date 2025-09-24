<?php

/** @var yii\web\View $this */

$this->title = 'Pokédex API - Yii2';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">¡Exámenes!</h1>

        <p class="lead">Practica con nuestro sistema de exámenes de Auditoría de Sistemas.</p>

        <p>
            <a class="btn btn-lg btn-warning" href="<?= \yii\helpers\Url::to(['examen/index']) ?>">Tomar Examen</a>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            
            <div class="col-lg-4">
                <h2>Sistema de Exámenes</h2>

                <p>Pon a prueba tus conocimientos de Auditoría de Sistemas con nuestro sistema de exámenes interactivo. Incluye diferentes tipos de preguntas y retroalimentación detallada.</p>

                <p><a class="btn btn-outline-secondary" href="<?= \yii\helpers\Url::to(['examen/index']) ?>">Tomar Examen &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
