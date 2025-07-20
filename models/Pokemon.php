<?php

namespace app\models;

use yii\base\Model;
use yii\httpclient\Client;

/**
 * Modelo para manejar datos de Pokémon desde la API de PokéAPI
 */
class Pokemon extends Model
{
    public $id;
    public $name;
    public $height;
    public $weight;
    public $base_experience;
    public $sprites;
    public $types;
    public $abilities;
    public $stats;
    
    private $apiUrl = 'https://pokeapi.co/api/v2/pokemon/';

    
    public function getPokemon($identifier)
    {
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->apiUrl . strtolower($identifier))
                ->send();

            if ($response->isOk) {
                $data = $response->data;
                
                // Asignar datos al modelo
                $this->id = $data['id'];
                $this->name = ucfirst($data['name']);
                $this->height = $data['height'];
                $this->weight = $data['weight'];
                $this->base_experience = $data['base_experience'];
                $this->sprites = $data['sprites'];
                $this->types = $data['types'];
                $this->abilities = $data['abilities'];
                $this->stats = $data['stats'];
                
                return $this;
            }
        } catch (\Exception $e) {
            \Yii::error('Error al obtener Pokémon: ' . $e->getMessage());
        }
        
        return null;
    }
    
    public function getPokemonList($limit = 20, $offset = 0)
    {
        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->apiUrl)
                ->setData(['limit' => $limit, 'offset' => $offset])
                ->send();

            if ($response->isOk) {
                return $response->data;
            }
        } catch (\Exception $e) {
            \Yii::error('Error al obtener lista de Pokémon: ' . $e->getMessage());
        }
        
        return ['results' => []];
    }

    /**
     * Obtiene los tipos de un Pokémon formateados
     * @return string
     */
    public function getTypesFormatted()
    {
        if (!$this->types) {
            return '';
        }
        
        $typeNames = [];
        foreach ($this->types as $type) {
            $typeNames[] = ucfirst($type['type']['name']);
        }
        
        return implode(', ', $typeNames);
    }

    /**
     * Obtiene las habilidades de un Pokémon formateadas
     * @return string
     */
    public function getAbilitiesFormatted()
    {
        if (!$this->abilities) {
            return '';
        }
        
        $abilityNames = [];
        foreach ($this->abilities as $ability) {
            $abilityNames[] = ucfirst($ability['ability']['name']);
        }
        
        return implode(', ', $abilityNames);
    }

    /**
     * Obtiene la imagen frontal del Pokémon
     * @return string|null
     */
    public function getFrontImage()
    {
        return $this->sprites['front_default'] ?? null;
    }

    /**
     * Convierte altura de decímetros a metros
     * @return float
     */
    public function getHeightInMeters()
    {
        return $this->height / 10;
    }

    /**
     * Convierte peso de hectogramos a kilogramos
     * @return float
     */
    public function getWeightInKg()
    {
        return $this->weight / 10;
    }
}
