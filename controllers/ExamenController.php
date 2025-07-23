<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Session;
use app\models\Pregunta;

/**
 * Controlador para el sistema de exámenes
 */
class ExamenController extends Controller
{
    /**
     * Página principal del examen
     */
    public function actionIndex()
    {
        $preguntas = Pregunta::getPreguntasActivas();
        $categorias = Pregunta::getCategorias();
        
        return $this->render('index', [
            'preguntas' => $preguntas,
            'categorias' => $categorias,
            'totalPreguntas' => count($preguntas)
        ]);
    }

    /**
     * Iniciar el examen
     */
    public function actionIniciar()
    {
        $session = Yii::$app->session;
        
        // Reiniciar el examen
        $session->remove('examen_iniciado');
        $session->remove('pregunta_actual');
        $session->remove('respuestas_examen');
        $session->remove('tiempo_inicio');
        
        // Configurar nuevo examen
        $session->set('examen_iniciado', true);
        $session->set('pregunta_actual', 1);
        $session->set('respuestas_examen', []);
        $session->set('tiempo_inicio', time());
        
        return $this->redirect(['pregunta', 'indice' => 1]);
    }

    /**
     * Mostrar pregunta específica
     */
    public function actionPregunta($indice = 1)
    {
        $session = Yii::$app->session;
        
        if (!$session->get('examen_iniciado')) {
            return $this->redirect(['index']);
        }
        
        $preguntas = Pregunta::getPreguntasActivas();
        $pregunta = null;
        
        // Buscar pregunta por índice consecutivo (1, 2, 3, etc.)
        if ($indice >= 1 && $indice <= count($preguntas)) {
            $pregunta = $preguntas[$indice - 1]; // Array es 0-indexed
        }
        
        if (!$pregunta) {
            Yii::$app->session->setFlash('error', 'Pregunta no encontrada.');
            return $this->redirect(['index']);
        }
        
        $totalPreguntas = count($preguntas);
        $respuestasGuardadas = $session->get('respuestas_examen', []);
        $preguntaActual = $indice;
        
        return $this->render('pregunta', [
            'pregunta' => $pregunta,
            'preguntaActual' => $preguntaActual,
            'totalPreguntas' => $totalPreguntas,
            'respuestaGuardada' => $respuestasGuardadas[$pregunta['id']] ?? null
        ]);
    }

    /**
     * Guardar respuesta y navegar
     */
    public function actionResponder()
    {
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        
        if (!$session->get('examen_iniciado')) {
            return $this->redirect(['index']);
        }
        
        $preguntaId = $request->post('pregunta_id');
        $indiceActual = $request->post('indice_actual', 1);
        $respuesta = $request->post('respuesta');
        $accion = $request->post('accion', 'guardar');
        
        // Guardar respuesta
        $respuestas = $session->get('respuestas_examen', []);
        $respuestas[$preguntaId] = $respuesta;
        $session->set('respuestas_examen', $respuestas);
        
        $preguntas = Pregunta::getPreguntasActivas();
        $totalPreguntas = count($preguntas);
        
        // Manejar diferentes acciones
        switch ($accion) {
            case 'anterior':
                if ($indiceActual > 1) {
                    return $this->redirect(['pregunta', 'indice' => $indiceActual - 1]);
                }
                break;
                
            case 'siguiente':
                if ($indiceActual < $totalPreguntas) {
                    return $this->redirect(['pregunta', 'indice' => $indiceActual + 1]);
                }
                break;
                
            case 'finalizar':
                return $this->redirect(['finalizar']);
                
            case 'auto_guardar':
                // Para auto-guardado silencioso, solo retornar status
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'guardado'];
                
            case 'guardar':
            default:
                // Solo guardar y quedarse en la misma pregunta
                Yii::$app->session->setFlash('success', 'Respuesta guardada correctamente.');
                break;
        }
        
        return $this->redirect(['pregunta', 'indice' => $indiceActual]);
    }

    /**
     * Navegar a pregunta específica
     */
    public function actionNavegar($indice)
    {
        $session = Yii::$app->session;
        
        if (!$session->get('examen_iniciado')) {
            return $this->redirect(['index']);
        }
        
        return $this->redirect(['pregunta', 'indice' => $indice]);
    }

    /**
     * Finalizar examen y mostrar resultados
     */
    public function actionFinalizar()
    {
        $session = Yii::$app->session;
        
        if (!$session->get('examen_iniciado')) {
            return $this->redirect(['index']);
        }
        
        $respuestas = $session->get('respuestas_examen', []);
        $tiempoInicio = $session->get('tiempo_inicio');
        $tiempoFin = time();
        $tiempoTotal = $tiempoFin - $tiempoInicio;
        
        // Calcular resultados
        $resultados = Pregunta::calcularPuntaje($respuestas);
        $resultados['tiempo_total'] = $tiempoTotal;
        
        // Limpiar sesión
        $session->remove('examen_iniciado');
        $session->remove('pregunta_actual');
        $session->remove('respuestas_examen');
        $session->remove('tiempo_inicio');
        
        return $this->render('resultados', [
            'resultados' => $resultados
        ]);
    }

    /**
     * Obtener progreso del examen via AJAX
     */
    public function actionProgreso()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $session = Yii::$app->session;
        $respuestas = $session->get('respuestas_examen', []);
        $preguntas = Pregunta::getPreguntasActivas();
        
        return [
            'total' => count($preguntas),
            'respondidas' => count($respuestas),
            'porcentaje' => (count($respuestas) / count($preguntas)) * 100
        ];
    }
}
