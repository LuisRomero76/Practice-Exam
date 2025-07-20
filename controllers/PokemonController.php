<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Pokemon;

/**
 * Controlador para manejar acciones relacionadas con Pokémon
 */
class PokemonController extends Controller
{
    /**
     * Lista de Pokémon con paginación
     */
    public function actionIndex()
    {
        $pokemon = new Pokemon();
        $page = Yii::$app->request->get('page', 1);
        $limit = 100;
        $offset = ($page - 1) * $limit;
        
        $pokemonList = $pokemon->getPokemonList($limit, $offset);
        
        return $this->render('index', [
            'pokemonList' => $pokemonList,
            'currentPage' => $page,
            'limit' => $limit,
        ]);
    }

    /**
     * Ver detalles de un Pokémon específico
     */
    public function actionView($id)
    {
        $pokemon = new Pokemon();
        $pokemonData = $pokemon->getPokemon($id);
        
        if ($pokemonData === null) {
            throw new NotFoundHttpException('El Pokémon solicitado no existe.');
        }
        
        return $this->render('view', [
            'pokemon' => $pokemonData,
        ]);
    }

    /**
     * Buscar Pokémon por nombre
     */
    public function actionSearch()
    {
        $query = Yii::$app->request->get('q', '');
        $pokemon = null;
        $error = null;
        
        if (!empty($query)) {
            $pokemonModel = new Pokemon();
            $pokemon = $pokemonModel->getPokemon($query);
            
            if ($pokemon === null) {
                $error = 'No se encontró ningún Pokémon con el nombre: ' . $query;
            }
        }
        
        return $this->render('search', [
            'query' => $query,
            'pokemon' => $pokemon,
            'error' => $error,
        ]);
    }

    /**
     * API endpoint para obtener datos de Pokémon en JSON
     */
    public function actionApi($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $pokemon = new Pokemon();
        $pokemonData = $pokemon->getPokemon($id);
        
        if ($pokemonData === null) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Pokémon no encontrado'];
        }
        
        return [
            'id' => $pokemonData->id,
            'name' => $pokemonData->name,
            'height' => $pokemonData->getHeightInMeters(),
            'weight' => $pokemonData->getWeightInKg(),
            'base_experience' => $pokemonData->base_experience,
            'types' => $pokemonData->getTypesFormatted(),
            'abilities' => $pokemonData->getAbilitiesFormatted(),
            'image' => $pokemonData->getFrontImage(),
        ];
    }
}
