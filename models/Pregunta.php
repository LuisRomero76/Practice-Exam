<?php

namespace app\models;

use yii\base\Model;

/**
 * Modelo para manejar preguntas de examen
 */
class Pregunta extends Model
{
    // Tipos de preguntas
    const TIPO_SELECCION_UNICA = 'seleccion_unica';
    const TIPO_SELECCION_MULTIPLE = 'seleccion_multiple';
    const TIPO_VERDADERO_FALSO = 'verdadero_falso';
    const TIPO_COMPLETAR = 'completar';
    const TIPO_SELECT = 'select';
    const TIPO_RELACIONAR = 'relacionar'; // Nuevo tipo

    public $id;
    public $pregunta;
    public $tipo;
    public $opciones = [];
    public $respuesta_correcta;
    public $explicacion;
    public $categoria;

    /**
     * Obtiene solo las preguntas activas (no comentadas) con índices consecutivos
     * @return array
     */
    public static function getPreguntasActivas()
    {
        $todasLasPreguntas = self::getPreguntasExamen();
        $preguntasActivas = [];
        $indice = 1;
        
        foreach ($todasLasPreguntas as $pregunta) {
            // Solo incluir preguntas que tengan todos los campos requeridos
            if (isset($pregunta['id']) && isset($pregunta['pregunta']) && isset($pregunta['tipo'])) {
                // Crear una copia de la pregunta con nuevo índice
                $preguntaActiva = $pregunta;
                $preguntaActiva['indice'] = $indice;
                $preguntasActivas[] = $preguntaActiva;
                $indice++;
            }
        }
        
        return $preguntasActivas;
    }

    /**
     * Obtiene todas las preguntas del examen
     * @return array
     */
    public static function getPreguntasExamen()
    {
        return [
            // PREGUNTAS COMENTADAS TEMPORALMENTE - YA DOMINADAS (1-13, 15-22, 25, 27, 29, 34, 35, 36, 39, 43, 45)
            /*
            [
                'id' => 1,
                'pregunta' => 'Relacione cada amenaza con su tipo correspondiente (STRIDE)',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'descubrimiento' => 'Descubrimiento de la información',
                    'spoofing' => 'Spoofing',
                    'repudio' => 'Repudio',
                    'escalamiento' => 'Escalamiento de Privilegios',
                    'tampering' => 'Tampering'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'sniffing' => 'Sniffing',
                    'navegacion_invisible' => 'Navegación invisible',
                    'desfragmentacion' => 'Desfragmentación de Disco Duro',
                    'usuario_privilegios' => 'Usuario con derechos por encima de su rol',
                    'sql_injection' => 'SQL Inyección'
                ],
                'respuesta_correcta' => [
                    'descubrimiento' => 'sniffing',
                    'spoofing' => 'navegacion_invisible',
                    'repudio' => 'desfragmentacion',
                    'escalamiento' => 'usuario_privilegios',
                    'tampering' => 'sql_injection'
                ],
                'explicacion' => 'Las relaciones correctas son: Descubrimiento → Sniffing, Spoofing → Navegación invisible, Repudio → Desfragmentación, Escalamiento → Usuario con privilegios, Tampering → SQL Injection.',
                'categoria' => 'Seguridad - STRIDE'
            ],*/
            /*
            [
                'id' => 2,
                'pregunta' => 'La materialidad de la evidencia en una auditoria se refiere a:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'A una conclusión del auditor que no es discutible',
                    'b' => 'Evidencia digital de un hecho ocurrido',
                    'c' => 'Evidencia asociada a los hechos investigados',
                    'd' => 'Comprueba un riesgo prioritario de la auditoria'
                ],
                'respuesta_correcta' => 'd',
                'explicacion' => 'La materialidad se refiere a la importancia relativa de la evidencia para comprobar riesgos prioritarios.',
                'categoria' => 'Auditoría'
            ],
            [
                'id' => 3,
                'pregunta' => '¿Cuál de los siguientes cumple con la función disuasiva?',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Cámaras de Vigilancia',
                    'b' => 'Backups',
                    'c' => 'Cerraduras biométricas',
                    'd' => 'Un solo punto de acceso'
                ],
                'respuesta_correcta' => 'a',
                'explicacion' => 'Las cámaras de vigilancia tienen función disuasiva porque previenen actos indebidos por su presencia visible.',
                'categoria' => 'Controles de Seguridad'
            ],
            [
                'id' => 4,
                'pregunta' => 'Relacione con el par correcto',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'admin_recursos' => 'Administración de Recursos',
                    'medicion_desempeno' => 'Medición del Desempeño',
                    'admin_riesgos' => 'Administración de Riesgos',
                    'entrega_valor' => 'Entrega de Valor',
                    'alineamiento' => 'Alineamiento estratégico'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'cuidar_inversion' => 'Cuidar la inversión en TI y administrarla adecuadamente',
                    'metas_medibles' => 'Establecer metas medibles y hacer un monitoreo de la entrega del servicio',
                    'identificar_amenazas' => 'Identificar amenazas y niveles de aceptación del riesgo',
                    'beneficios_esperados' => 'Asegurarse de que las TI le dan a la empresa los beneficios esperados',
                    'concordancia_operaciones' => 'Las operaciones de TI deben estar en concordancia con las operaciones del negocio'
                ],
                'respuesta_correcta' => [
                    'admin_recursos' => 'cuidar_inversion',
                    'medicion_desempeno' => 'metas_medibles',
                    'admin_riesgos' => 'identificar_amenazas',
                    'entrega_valor' => 'beneficios_esperados',
                    'alineamiento' => 'concordancia_operaciones'
                ],
                'explicacion' => 'Cada área de gestión de TI tiene objetivos específicos que deben ser relacionados correctamente.',
                'categoria' => 'Gestión de TI'
            ],
            [
                'id' => 5,
                'pregunta' => 'Se dice que la auditoria es una crítica que se hace al sistema porque:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Es un examen o valoración',
                    'b' => 'Encuentran los posibles defectos en el control',
                    'c' => 'Es imparcial',
                    'd' => 'Destaca los aspectos positivos y negativos del control'
                ],
                'respuesta_correcta' => 'd',
                'explicacion' => 'La auditoría es una crítica constructiva que evalúa tanto fortalezas como debilidades del sistema.',
                'categoria' => 'Auditoría'
            ],
            [
                'id' => 6,
                'pregunta' => 'La fase de Nolan donde ya se adoptan estándares para todos los productos del servicio informático, más allá de la administración y control de los mismos, es la fase de:',
                'tipo' => self::TIPO_COMPLETAR,
                'opciones' => [],
                'respuesta_correcta' => 'integración',
                'explicacion' => 'En la fase de Integración se adoptan estándares unificados para todos los productos y servicios informáticos.',
                'categoria' => 'Fases de Nolan'
            ],
            [
                'id' => 7,
                'pregunta' => 'A menor cantidad de pruebas de cumplimiento exitosas, mayor será la cantidad de pruebas sustantivas que realizar.',
                'tipo' => self::TIPO_VERDADERO_FALSO,
                'opciones' => [
                    'verdadero' => 'Verdadero',
                    'falso' => 'Falso'
                ],
                'respuesta_correcta' => 'verdadero',
                'explicacion' => 'Si los controles no funcionan bien (pocas pruebas de cumplimiento exitosas), se requieren más pruebas sustantivas.',
                'categoria' => 'Auditoría'
            ],
            [
                'id' => 8,
                'pregunta' => 'Si se decide proteger a través de un IDS de host a sistemas críticos es porque se quiere garantizar principalmente:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'La disponibilidad',
                    'b' => 'El no repudio',
                    'c' => 'La autenticidad',
                    'd' => 'La Integridad'
                ],
                'respuesta_correcta' => 'd',
                'explicacion' => 'Los IDS de host están diseñados principalmente para detectar alteraciones y garantizar la integridad del sistema.',
                'categoria' => 'Sistemas IDS'
            ],
            [
                'id' => 9,
                'pregunta' => 'Complete usando el elemento correcto',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'elemento1' => 'Las _____ aprovechan las',
                    'elemento2' => '_____ para generar',
                    'elemento3' => '_____ de robo, perdida o daño en los',
                    'elemento4' => '_____ informáticos'
                ],
                'opciones_select' => [
                    '' => 'Seleccione...',
                    'amenazas' => 'Amenazas',
                    'vulnerabilidades' => 'Vulnerabilidades',
                    'riesgo' => 'Riesgo',
                    'activos' => 'Activos',
                    'controles' => 'Controles',
                    'debilidades' => 'Debilidades'
                ],
                'respuesta_correcta' => [
                    'elemento1' => 'amenazas',
                    'elemento2' => 'vulnerabilidades',
                    'elemento3' => 'riesgo',
                    'elemento4' => 'activos'
                ],
                'explicacion' => 'Las amenazas aprovechan las vulnerabilidades para generar riesgo en los activos informáticos.',
                'categoria' => 'Gestión de Riesgos'
            ],*/
            /*[
                'id' => 10,
                'pregunta' => 'Un principio de la auditoria operacional utilizada en la auditoria del sistema es:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'La eficiencia de usuarios y administradores de TI',
                    'b' => 'La exactitud de los resultados del sistema',
                    'c' => 'La protección de los activos informáticos',
                    'd' => 'La oportunidad de los datos que presenta un sistema'
                ],
                'respuesta_correcta' => 'a',
                'explicacion' => 'La auditoría operacional se enfoca en la eficiencia de procesos y personal, incluyendo usuarios y administradores.',
                'categoria' => 'Auditoría Operacional'
            ],
            [
                'id' => 11,
                'pregunta' => 'El riesgo inherente es aquel que:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Existe, pero es despreciable',
                    'b' => 'Existe sin control definido',
                    'c' => 'Es aquel que está controlado en niveles deseados',
                    'd' => 'Es el que queda después de aplicar control'
                ],
                'respuesta_correcta' => 'b',
                'explicacion' => 'El riesgo inherente es el que existe naturalmente en una actividad, antes de aplicar cualquier control.',
                'categoria' => 'Gestión de Riesgos'
            ],
            [
                'id' => 12,
                'pregunta' => 'En cuanto a las normas generales para hacer auditoria, la "Relación Organizacional" se refiere a:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Establecimiento de responsabilidad y autoridad para el auditor',
                    'b' => 'Independencia profesional del auditor',
                    'c' => 'Completar de manera objetiva la auditoria',
                    'd' => 'Supervisión del trabajo de auditoria'
                ],
                'respuesta_correcta' => 'b',
                'explicacion' => 'La relación organizacional establece la independencia profesional necesaria del auditor.',
                'categoria' => 'Normas de Auditoría'
            ],
            [
                'id' => 13,
                'pregunta' => 'Si el control está bien establecido en un sistema de información de tal manera que el nivel de riesgo se bajó, entonces habrá que realizar menos pruebas sustantivas.',
                'tipo' => self::TIPO_VERDADERO_FALSO,
                'opciones' => [
                    'verdadero' => 'Verdadero',
                    'falso' => 'Falso'
                ],
                'respuesta_correcta' => 'verdadero',
                'explicacion' => 'Cuando los controles son efectivos y reducen el riesgo, se requieren menos pruebas sustantivas.',
                'categoria' => 'Auditoría'
            ],*/
            [
                'id' => 14,
                'pregunta' => 'Asocie correctamente en función a los objetivos de control que se buscan',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'control_cambios' => 'Control de Cambios',
                    'operaciones_hw' => 'Operaciones sobre el HW',
                    'control_incidentes' => 'Control de Incidentes',
                    'control_problemas' => 'Control de Problemas',
                    'soporte_tecnico' => 'Soporte Técnico',
                    'uso_eficiente' => 'Uso eficiente de Recursos'
                ],
                'opciones_select' => [
                    '' => 'Seleccione...',
                    'separacion_apps' => 'Separación entre aplicaciones en desarrollo y aplicaciones en operación',
                    'cambios_equipos' => 'Control de cambios sobre los equipos',
                    'eventos_inesperados' => 'Aprender de eventos inesperados y establecer posibles soluciones',
                    'necesidades_identificadas' => 'Respuesta a necesidades ya identificadas previamente',
                    'mantenimiento_plataforma' => 'Mantenimiento de la plataforma operativa',
                    'evitar_uso_abusivo' => 'Evitar el uso abusivo de los equipos'
                ],
                'respuesta_correcta' => [
                    'control_cambios' => 'separacion_apps',
                    'operaciones_hw' => 'cambios_equipos',
                    'control_incidentes' => 'eventos_inesperados',
                    'control_problemas' => 'necesidades_identificadas',
                    'soporte_tecnico' => 'mantenimiento_plataforma',
                    'uso_eficiente' => 'evitar_uso_abusivo'
                ],
                'explicacion' => 'Cada tipo de control tiene objetivos específicos en la gestión de sistemas de información.',
                'categoria' => 'Objetivos de Control'
            ],
            /*[
                'id' => 15,
                'pregunta' => '¿Cuáles son características del control interno?',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'Es continuo',
                    'b' => 'Es preventivo',
                    'c' => 'Es correctivo únicamente',
                    'd' => 'Es opcional',
                    'e' => 'Es temporal'
                ],
                'respuesta_correcta' => ['a', 'b'],
                'explicacion' => 'El control interno debe ser continuo en el tiempo y preventivo en su naturaleza.',
                'categoria' => 'Control Interno'
            ],
            [
                'id' => 16,
                'pregunta' => 'En qué sección del contrato de auditoria se define la contraparte por parte de la Empresa para el Control del trabajo de Auditoria:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Alcance',
                    'b' => 'Obligación de rendir cuentas',
                    'c' => 'Objetivos',
                    'd' => 'Responsabilidad'
                ],
                'respuesta_correcta' => 'd',
                'explicacion' => 'En la sección de Responsabilidad se define quién será la contraparte empresarial para el control del trabajo.',
                'categoria' => 'Contrato de Auditoría'
            ],
            [
                'id' => 17,
                'pregunta' => 'Ubique correctamente cada elemento con su objetivo de control correspondiente',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'elemento_controlar' => 'Elemento a Controlar',
                    'objetivo_control' => 'Objetivo de Control',
                    'control' => 'Control',
                    'sensor' => 'Sensor',
                    'grupo_control' => 'Grupo de Control',
                    'grupo_activante' => 'Grupo de Activante'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'acceso_celular' => 'El acceso al celular',
                    'lograr_acceso_autorizado' => 'Lograr que solo acceda al celular la persona autorizada',
                    'sistema_acceso_huella' => 'Sistema de acceso basado en huella digital',
                    'sensor_huella_toma' => 'Sensor de huella digital - subsistema de toma de huella',
                    'sensor_huella_comparacion' => 'Sensor de huella digital - subs. de comparación',
                    'sensor_huella_acceso' => 'Sensor de huella digital - subs. de acceso'
                ],
                'respuesta_correcta' => [
                    'elemento_controlar' => 'acceso_celular',
                    'objetivo_control' => 'lograr_acceso_autorizado',
                    'control' => 'sistema_acceso_huella',
                    'sensor' => 'sensor_huella_toma',
                    'grupo_control' => 'sensor_huella_comparacion',
                    'grupo_activante' => 'sensor_huella_acceso'
                ],
                'explicacion' => 'En un sistema de control de acceso biométrico: Elemento a controlar → el acceso al celular, Objetivo → lograr acceso autorizado, Control → sistema de huella digital, Sensor → subsistema de toma, Grupo de control → subsistema de comparación, Grupo activante → subsistema de acceso.',
                'categoria' => 'Sistemas de Control'
            ],*/
            /*[
                'id' => 18,
                'pregunta' => 'Para que un BCP se active, las operaciones en cuestión deben ser las operaciones _____ del negocio.',
                'tipo' => self::TIPO_COMPLETAR,
                'opciones' => [],
                'respuesta_correcta' => 'críticas',
                'explicacion' => 'Un Plan de Continuidad del Negocio (BCP) se activa solo para operaciones críticas del negocio.',
                'categoria' => 'Plan de Continuidad'
            ],
            [
                'id' => 19,
                'pregunta' => '¿Cuál de las siguientes actividades es la que NO realiza un auditor?',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Determinar acciones correctivas',
                    'b' => 'Monitorear acciones correctivas',
                    'c' => 'Comparar o diagnosticar',
                    'd' => 'Ejecutar acciones correctivas',
                    'e' => 'Establecer Estándares'
                ],
                'respuesta_correcta' => 'd',
                'explicacion' => 'El auditor identifica, monitorea y establece estándares, pero NO ejecuta las acciones correctivas. Esa es responsabilidad de la administración.',
                'categoria' => 'Roles del Auditor'
            ],
            [
                'id' => 20,
                'pregunta' => 'Para determinar el nivel de exposición de un activo, es importante considerar los siguientes aspectos:',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'Vulnerabilidades posibles',
                    'b' => 'Controles definidos',
                    'c' => 'Riesgo posible',
                    'd' => 'Amenazas posibles',
                    'e' => 'Valor activo'
                ],
                'respuesta_correcta' => ['a', 'd'],
                'explicacion' => 'Para determinar la exposición de un activo se consideran principalmente las vulnerabilidades posibles y las amenazas posibles.',
                'categoria' => 'Análisis de Riesgos'
            ],
            [
                'id' => 21,
                'pregunta' => 'Si quiero tener un objetivo del punto de recuperación muy alto, entonces ¿cuál de los siguientes controles debo elegir?',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Hot Sites',
                    'b' => 'Servidores Fail Over',
                    'c' => 'Backups Diarios',
                    'd' => 'Cold Sites',
                    'e' => 'Espejamiento'
                ],
                'respuesta_correcta' => 'e',
                'explicacion' => 'El espejamiento (mirroring) proporciona el objetivo de punto de recuperación más alto ya que mantiene una copia exacta en tiempo real.',
                'categoria' => 'Recuperación de Desastres'
            ],
            [
                'id' => 22,
                'pregunta' => 'Si tengo un ROI del 50% significa que:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Por cada peso invertido en realidad estoy perdiendo medio peso porque el ROI debe ser mayor a 100%',
                    'b' => 'Por cada peso invertido recupero la mitad de ese peso en un tiempo dado',
                    'c' => 'Por cada peso invertido recupero el mismo además tengo medio peso adicional',
                    'd' => 'La inversión no es rentable'
                ],
                'respuesta_correcta' => 'c',
                'explicacion' => 'Un ROI del 50% significa que por cada peso invertido, recuperas tu inversión inicial más 50% adicional (medio peso más).',
                'categoria' => 'Análisis Financiero'
            ],*/
            [
                'id' => 23,
                'pregunta' => 'Asocie correctamente cada elemento con su tipo de control correspondiente',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'filtrado_trafico' => 'Reglas de filtrado de tráfico en un firewall para aislar la red interna de la red externa',
                    'control_riesgo' => 'Tipo de control para darse cuenta que estamos en riesgo (por ej. Una prueba covid-19)',
                    'actividades_personal' => 'Tipo de Control relacionado con las actividades del personal en el trabajo diario',
                    'debilidades_control' => 'Debilidades en el control o inexistencia del mismo',
                    'acceso_huella' => 'Acceso a ambientes por huella Digital'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'control_tecnico' => 'Control Técnico',
                    'control_detectivo' => 'Control Detectivo',
                    'control_administrativo' => 'Control Administrativo',
                    'vulnerabilidades' => 'Vulnerabilidades',
                    'control_fisico' => 'Control Físico',
                    'control_preventivo' => 'Control Preventivo'
                ],
                'respuesta_correcta' => [
                    'filtrado_trafico' => 'control_tecnico',
                    'control_riesgo' => 'control_detectivo',
                    'actividades_personal' => 'control_administrativo',
                    'debilidades_control' => 'vulnerabilidades',
                    'acceso_huella' => 'control_fisico'
                ],
                'explicacion' => 'Los controles se clasifican en: Técnicos (firewalls), Detectivos (identifican riesgos), Administrativos (políticas de personal), Vulnerabilidades (debilidades), y Físicos (acceso biométrico).',
                'categoria' => 'Tipos de Control'
            ],
            [
                'id' => 24,
                'pregunta' => 'Asocie cada valor amenazado con la amenaza correspondiente',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'sin_auditoria' => 'El sistema de la empresa no tiene un módulo de auditoría que le permita revisar las transacciones realizadas en el sistema',
                    'usuario_ventas' => 'Un usuario del área de ventas logra tener derechos de cuentas gerenciales que le permitan hacer desfalco',
                    'dispositivos_red' => 'Los dispositivos y cableado de red se encuentran al alcance de la gente extraña lo que podría permitir una intrusión en la red no autorizada',
                    'ataque_diccionario' => 'Una persona extraña logra deducir la contraseña de acceso del administrador a través de un ataque de diccionario',
                    'incendio_datacenter' => 'Incendio de data center, también se pierde los Backups que estaban ahí'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'no_repudio' => 'No repudio',
                    'autenticidad' => 'Autenticidad',
                    'integridad' => 'Integridad',
                    'confidencialidad' => 'Confidencialidad',
                    'disponibilidad' => 'Disponibilidad'
                ],
                'respuesta_correcta' => [
                    'sin_auditoria' => 'no_repudio',
                    'usuario_ventas' => 'autenticidad',
                    'dispositivos_red' => 'integridad',
                    'ataque_diccionario' => 'confidencialidad',
                    'incendio_datacenter' => 'disponibilidad'
                ],
                'explicacion' => 'Cada escenario amenaza un principio de seguridad específico: sin auditoría → no repudio, escalamiento de privilegios → autenticidad, acceso físico → integridad, robo de contraseñas → confidencialidad, pérdida física → disponibilidad.',
                'categoria' => 'Principios de Seguridad'
            ],
            /*[
                'id' => 25,
                'pregunta' => 'Según COBIT, que la información sea relevante y pertinente para los procesos de negocio y se proporcione de una manera oportuna, consistente y utilizable se refiere a:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Eficiencia',
                    'b' => 'Eficacia',
                    'c' => 'Efectividad',
                    'd' => 'Economía'
                ],
                'respuesta_correcta' => 'b',
                'explicacion' => 'La eficacia en COBIT se refiere a que la información sea relevante, pertinente, oportuna, consistente y utilizable para los procesos de negocio.',
                'categoria' => 'COBIT'
            ],*/
            [
                'id' => 26,
                'pregunta' => 'Asocie Objetivo de control con Dominio Val IT',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'gestion_inversion' => 'Gestión de la Inversión',
                    'gobierno_valor' => 'Gobierno de Valor',
                    'gestion_cartera' => 'Gestión de la Cartera'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'definir_caso_negocio' => 'Definir y documentar un caso de negocio detallado, incluyendo el detalle de los beneficios',
                    'definir_caracteristicas_cartera' => 'Definir las características de la cartera de Inversiones',
                    'evaluar_priorizar_seleccionar' => 'Evaluar, priorizar y seleccionar, aplazar o rechazar las inversiones'
                ],
                'respuesta_correcta' => [
                    'gestion_inversion' => 'definir_caso_negocio',
                    'gobierno_valor' => 'definir_caracteristicas_cartera',
                    'gestion_cartera' => 'evaluar_priorizar_seleccionar'
                ],
                'explicacion' => 'En VAL IT: Gestión de la Inversión define casos de negocio, Gobierno de Valor define características de cartera, y Gestión de la Cartera evalúa y prioriza inversiones.',
                'categoria' => 'VAL IT'
            ],
            /*[
                'id' => 27,
                'pregunta' => 'Etapa ITIL donde se provee un servicio de atención al cliente:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Estrategia del servicio',
                    'b' => 'Diseño del servicio',
                    'c' => 'Operación del servicio',
                    'd' => 'Transición del servicio'
                ],
                'respuesta_correcta' => 'c',
                'explicacion' => 'La operación del servicio es la etapa donde se proveen los servicios de atención al cliente y se ejecutan las operaciones diarias.',
                'categoria' => 'ITIL'
            ],*/
            [
                'id' => 28,
                'pregunta' => 'Cuál de las siguientes expresa mejor la diferencia entre tratamiento del riesgo y mitigación del riesgo:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Mitigación es la reducción de la ocurrencia del riesgo, tratamiento incluye al anterior.',
                    'b' => 'Mitigación es la reducción de la ocurrencia del riesgo o bien la minimización del impacto, tratamiento incluye al anterior.',
                    'c' => 'La mitigación es la recuperación después de ocurrido el riesgo, el tratamiento es la prevención de la ocurrencia del riesgo.',
                    'd' => 'Ambos se pueden manejar indistintamente para reflejar el mismo concepto'
                ],
                'respuesta_correcta' => 'b',
                'explicacion' => 'La mitigación puede reducir tanto la probabilidad como el impacto del riesgo, mientras que el tratamiento es un concepto más amplio que incluye mitigación, aceptación, transferencia y evitación.',
                'categoria' => 'Gestión de Riesgos'
            ],
            /*[
                'id' => 29,
                'pregunta' => 'Cómo se complementa la ISO27001 con la ISO27002, o sea en qué etapa de la implementación de la ISO27001 se incluye o utiliza en mayor medida la ISO27002?',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Identificación de Riesgos',
                    'b' => 'Tratamiento de los Riesgos',
                    'c' => 'Revisión de la Política de Seguridad',
                    'd' => 'Establecimiento de Roles y Responsabilidades'
                ],
                'respuesta_correcta' => 'b',
                'explicacion' => 'ISO27002 proporciona controles específicos que se utilizan principalmente en la fase de tratamiento de riesgos de ISO27001.',
                'categoria' => 'ISO 27001/27002'
            ],*/
            [
                'id' => 30,
                'pregunta' => 'Cuáles de los siguientes son elementos en común que tienen los modelos o estándares de control genéricos vistos (ITIL, COBIT, ISO27001, ISO27002):',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'Poseen dominios, procesos, criterios de información y recursos de TI',
                    'b' => 'La gestión de riesgos de TI como parte importante del modelo',
                    'c' => 'Están orientados por objetivos de control',
                    'd' => 'Poseen un ciclo de vida de mejora continua',
                    'e' => 'Se acomodan a cualquier contexto de uso y aplicación de la TI'
                ],
                'respuesta_correcta' => ['b', 'd', 'e'],
                'explicacion' => 'Los elementos comunes son: gestión de riesgos, ciclo de mejora continua y adaptabilidad a diferentes contextos.',
                'categoria' => 'Estándares de Control'
            ],
            [
                'id' => 31,
                'pregunta' => '2 diferencias entre ITIL v3 y COBIT5 son:',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'ITIL tiene un ciclo de vida para basado en el alineamiento estratégico de TI y COBIT 5 tiene dominios y procesos de TI',
                    'b' => 'COBIT 5 está más orientado al "QUÉ" se debe controlar e ITIL está más orientado al "CÓMO"',
                    'c' => 'ITIL gestión de TI, COBIT gestión de Servicios',
                    'd' => 'ITIL está orientado a la mejora del control de TI y COBIT a la mejora de los servicios de TI',
                    'e' => 'ITIL está orientado a la mejora de los servicios de TI y COBIT más a la mejora del control de los procesos de TI'
                ],
                'respuesta_correcta' => ['b', 'e'],
                'explicacion' => 'COBIT se enfoca en QUÉ controlar (marcos de control), ITIL en CÓMO hacerlo (gestión de servicios). ITIL mejora servicios, COBIT mejora control de procesos.',
                'categoria' => 'ITIL vs COBIT'
            ],
            [
                'id' => 32,
                'pregunta' => 'Son características de ITIL:',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'Está centrado en la valoración de riesgos de TI',
                    'b' => 'Es certificable para Organizaciones',
                    'c' => 'Orientado a la Gestión de servicios de TI',
                    'd' => 'Aplica un ciclo de vida de mejora continua',
                    'e' => 'Aplicable a todo tipo de entornos de TI'
                ],
                'respuesta_correcta' => ['c', 'd'],
                'explicacion' => 'ITIL se caracteriza por estar orientado a la gestión de servicios de TI y aplicar un ciclo de vida de mejora continua.',
                'categoria' => 'ITIL'
            ],
            [
                'id' => 33,
                'pregunta' => 'Dado que la ISO27002 no es certificable y es un anexo a la ISO27001, se podría implementar en una organización sin necesidad de implementar la ISO27001.',
                'tipo' => self::TIPO_VERDADERO_FALSO,
                'opciones' => [
                    'verdadero' => 'Verdadero',
                    'falso' => 'Falso'
                ],
                'respuesta_correcta' => 'falso',
                'explicacion' => 'Aunque técnicamente es posible usar ISO27002 independientemente, pierde su efectividad sin el marco de gestión de ISO27001.',
                'categoria' => 'ISO 27001/27002'
            ],
            /*[
                'id' => 34,
                'pregunta' => 'COBIT 5 es la integración de COBIT 4.1 junto a los modelos VAL IT y RISK IT, que permiten una separación clara entre de la Gobernanza y Gestión de TI',
                'tipo' => self::TIPO_VERDADERO_FALSO,
                'opciones' => [
                    'verdadero' => 'Verdadero',
                    'falso' => 'Falso'
                ],
                'respuesta_correcta' => 'verdadero',
                'explicacion' => 'COBIT 5 efectivamente integra COBIT 4.1, VAL IT y RISK IT, estableciendo una clara separación entre gobernanza y gestión.',
                'categoria' => 'COBIT'
            ],
            [
                'id' => 35,
                'pregunta' => 'El valor opuesto que se deduce de esta amenaza: Phishing',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Falsificación',
                    'b' => 'Integridad',
                    'c' => 'Modificación',
                    'd' => 'Autenticidad',
                    'e' => 'No Repudio'
                ],
                'respuesta_correcta' => 'a',
                'explicacion' => 'El phishing es una forma de falsificación donde se suplanta la identidad de entidades legítimas.',
                'categoria' => 'Amenazas de Seguridad'
            ],
            [
                'id' => 36,
                'pregunta' => 'Si el examen parcial de una materia es un SENSOR, entonces el DOCENTE es del grupo de control, el elemento a controlar es el aprovechamiento del estudiante, entonces el GRUPO ACTIVANTE es:',
                'tipo' => self::TIPO_COMPLETAR,
                'opciones' => [],
                'respuesta_correcta' => 'estudiante',
                'explicacion' => 'En un sistema de control académico, el estudiante es el grupo activante que debe responder a las acciones correctivas del docente.',
                'categoria' => 'Sistemas de Control'
            ],*/
            [
                'id' => 37,
                'pregunta' => 'Un programador que también es usuario de una aplicación que él mismo desarrolló para una empresa, podría cometer fraude aprovechando su doble rol. Valor amenazado:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Disponibilidad',
                    'b' => 'Autenticidad',
                    'c' => 'Integridad',
                    'd' => 'No repudio'
                ],
                'respuesta_correcta' => 'c',
                'explicacion' => 'El doble rol permite al programador modificar datos o código de manera no autorizada, amenazando la integridad.',
                'categoria' => 'Principios de Seguridad'
            ],
            [
                'id' => 38,
                'pregunta' => 'A través de un ataque de escalamiento de privilegios en el sistema, un simple vendedor de la empresa logra tener acceso a una cuenta gerencial. Valor amenazado:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Integridad',
                    'b' => 'Confidencialidad',
                    'c' => 'No repudio',
                    'd' => 'Autenticidad'
                ],
                'respuesta_correcta' => 'd',
                'explicacion' => 'El escalamiento de privilegios compromete la autenticidad al permitir que alguien actúe con credenciales que no le corresponden.',
                'categoria' => 'Principios de Seguridad'
            ],
            /*[
                'id' => 39,
                'pregunta' => 'Si la variable observada es un número de 8 dígitos pasando por la sintaxis semántica y relevancia, un ejemplo de información en relación a esa variable podría ser:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Que es un número de celular de una persona',
                    'b' => 'Que es un número de celular',
                    'c' => 'Que es un número que puede ser un celular o un carnet, dependiendo del caso',
                    'd' => 'Que es un número de celular del docente de una materia en la que estoy matriculado'
                ],
                'respuesta_correcta' => 'd',
                'explicacion' => 'La información más completa incluye contexto específico y relevancia personal, como la relación académica.',
                'categoria' => 'Gestión de Información'
            ],*/
            [
                'id' => 40,
                'pregunta' => 'Cuáles de los siguientes son un control de influencia directiva:',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'Se definen niveles de acceso para el uso de un sistema',
                    'b' => 'Se establecen roles para el equipo de desarrollo de Software',
                    'c' => 'Se hace una planificación de un proyecto de desarrollo de software',
                    'd' => 'Se establece sanción para todos aquellos alumnos que lleguen atrasados con más de 10 minutos'
                ],
                'respuesta_correcta' => ['b', 'c'],
                'explicacion' => 'Los controles de influencia directiva incluyen establecimiento de roles y planificación, que guían el comportamiento organizacional.',
                'categoria' => 'Tipos de Control'
            ],
            [
                'id' => 41,
                'pregunta' => 'Son principios de la auditoría contable que se aplican en la auditoría de sistemas:',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'La protección de los activos informáticos',
                    'b' => 'La exactitud de los resultados del sistema',
                    'c' => 'La eficiencia operacional de usuarios y administradores de TI',
                    'd' => 'La oportunidad de los datos que presenta un sistema'
                ],
                'respuesta_correcta' => ['a', 'b', 'd'],
                'explicacion' => 'Los principios aplicables son: protección de activos, exactitud de resultados y oportunidad de datos.',
                'categoria' => 'Auditoría de Sistemas'
            ],
            [
                'id' => 42,
                'pregunta' => 'Seleccione las afirmaciones ciertas en relación a COBIT:',
                'tipo' => self::TIPO_SELECCION_MULTIPLE,
                'opciones' => [
                    'a' => 'Los recursos de TI proveen información a los procesos de negocios para alcanzar los objetivos de negocio',
                    'b' => 'Las necesidades de Recursos de TI conducen a las inversiones en el Negocio para alcanzar los objetivos de negocio',
                    'c' => 'Los procesos de TI proveen información a los procesos de negocio para alcanzar los objetivos de negocio',
                    'd' => 'Las necesidades del negocio conducen las inversiones de Recursos de TI que son usados por los procesos de TI'
                ],
                'respuesta_correcta' => ['c', 'd'],
                'explicacion' => 'En COBIT: los procesos de TI (no recursos) proveen información, y las necesidades del negocio conducen las inversiones en TI.',
                'categoria' => 'COBIT'
            ],
            /*[
                'id' => 43,
                'pregunta' => 'Además de la ISO31000, para la gestión de riesgos, el auditor puede utilizar otro estándar ISO muy conocido y muy aplicable a todo entorno de control, este estándar es la ISO:',
                'tipo' => self::TIPO_COMPLETAR,
                'opciones' => [],
                'respuesta_correcta' => '27005',
                'explicacion' => 'ISO 27005 es el estándar específico para gestión de riesgos de seguridad de la información.',
                'categoria' => 'Estándares ISO'
            ],*/
            [
                'id' => 44,
                'pregunta' => 'Si el emisor encripta un mensaje con la clave pública del receptor, entonces se garantiza:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'La confidencialidad para el receptor',
                    'b' => 'La autenticidad del emisor',
                    'c' => 'Que varios receptores que conozcan la clave pública podrán abrir el mensaje',
                    'd' => 'Que el receptor podrá ver el mensaje del emisor usando su clave pública'
                ],
                'respuesta_correcta' => 'a',
                'explicacion' => 'Encriptar con la clave pública del receptor garantiza que solo él puede descifrarlo con su clave privada, asegurando confidencialidad.',
                'categoria' => 'Criptografía'
            ],
            /*[
                'id' => 45,
                'pregunta' => 'Qué algoritmos se podrían usar en una firma digital:',
                'tipo' => self::TIPO_SELECCION_UNICA,
                'opciones' => [
                    'a' => 'Asimétricos y de hashing',
                    'b' => 'Solo de hashing',
                    'c' => 'Simétricos y de hashing',
                    'd' => 'Asimétricos, simétricos y de hashing'
                ],
                'respuesta_correcta' => 'a',
                'explicacion' => 'Las firmas digitales requieren algoritmos asimétricos (para firmar/verificar) y de hashing (para resumir el mensaje).',
                'categoria' => 'Criptografía'
            ],*/
            [
                'id' => 46,
                'pregunta' => 'Asocie Proceso con Actividad relacionada en función a su objetivo de control',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'aplicar_iso31000' => 'Aplicar ISO31000',
                    'desarrollar_poa' => 'Desarrollar un POA',
                    'aplicar_tdd' => 'Aplicar TDD',
                    'planificacion_tactica' => 'Planificación táctica de sistemas',
                    'cumplir_asfi' => 'Cumplir con obligaciones regulatorias de la ASFI para entidades financieras',
                    'clasificacion_activos' => 'Clasificación de activos de información',
                    'segregacion_funciones' => 'Segregación de funciones',
                    'comparacion_alternativas' => 'Comparación de alternativas de solución tecnológica a una necesidad empresarial'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'po9_evaluar_riesgos' => 'PO9 Evaluar riesgos',
                    'po5_admin_inversiones' => 'PO5 Administrar Inversiones',
                    'po11_admin_calidad' => 'PO11 Administrar Calidad',
                    'po1_planeamiento_estrategico' => 'PO1 Planeamiento estratégico',
                    'po8_cumplimiento_externos' => 'PO8 Asegurar el cumplimiento de requerimientos externos',
                    'po2_arquitectura_informacion' => 'PO2 Arquitectura de la información',
                    'po4_organizacion_roles' => 'PO4 Organización y sus roles',
                    'po3_direccion_tecnologica' => 'PO3 Determinar la Dirección Tecnológica'
                ],
                'respuesta_correcta' => [
                    'aplicar_iso31000' => 'po9_evaluar_riesgos',
                    'desarrollar_poa' => 'po5_admin_inversiones',
                    'aplicar_tdd' => 'po11_admin_calidad',
                    'planificacion_tactica' => 'po1_planeamiento_estrategico',
                    'cumplir_asfi' => 'po8_cumplimiento_externos',
                    'clasificacion_activos' => 'po2_arquitectura_informacion',
                    'segregacion_funciones' => 'po4_organizacion_roles',
                    'comparacion_alternativas' => 'po3_direccion_tecnologica'
                ],
                'explicacion' => 'Cada actividad se relaciona con su proceso COBIT correspondiente: ISO31000→PO9, POA→PO5, TDD→PO11, planificación→PO1, ASFI→PO8, activos→PO2, segregación→PO4, alternativas→PO3.',
                'categoria' => 'Procesos COBIT'
            ],
            [
                'id' => 47,
                'pregunta' => 'Ordene correctamente el proceso de una auditoría:',
                'tipo' => self::TIPO_RELACIONAR,
                'conceptos' => [
                    'paso_1' => 'Primer paso',
                    'paso_2' => 'Segundo paso',
                    'paso_3' => 'Tercer paso',
                    'paso_4' => 'Cuarto paso',
                    'paso_5' => 'Quinto paso',
                    'paso_6' => 'Sexto paso'
                ],
                'opciones_select' => [
                    '' => 'Seleccione una opción...',
                    'recoleccion_evidencia' => 'Recolección de evidencia',
                    'informe_auditoria' => 'Informe de auditoría',
                    'programa_auditoria' => 'Programa de auditoría',
                    'evaluacion_fortalezas_debilidades' => 'Evaluación de fortalezas y debilidades del control',
                    'planeacion' => 'Planeación',
                    'monitoreo' => 'Monitoreo'
                ],
                'respuesta_correcta' => [
                    'paso_1' => 'planeacion',
                    'paso_2' => 'programa_auditoria',
                    'paso_3' => 'recoleccion_evidencia',
                    'paso_4' => 'evaluacion_fortalezas_debilidades',
                    'paso_5' => 'informe_auditoria',
                    'paso_6' => 'monitoreo'
                ],
                'explicacion' => 'El proceso correcto de auditoría es: 1) Planeación (definir objetivos y alcance), 2) Programa de auditoría (diseñar procedimientos), 3) Recolección de evidencia (ejecutar pruebas), 4) Evaluación de fortalezas y debilidades del control (análisis), 5) Informe de auditoría (reportar hallazgos), 6) Monitoreo (seguimiento de recomendaciones).',
                'categoria' => 'Proceso de Auditoría'
            ]
        ];
    }

    /**
     * Obtiene las categorías de preguntas
     * @return array
     */
    public static function getCategorias()
    {
        $preguntas = self::getPreguntasExamen();
        $categorias = [];
        
        foreach ($preguntas as $pregunta) {
            $categorias[$pregunta['categoria']] = $pregunta['categoria'];
        }
        
        return $categorias;
    }

    /**
     * Valida una respuesta
     * @param int $preguntaId
     * @param mixed $respuesta
     * @return array
     */
    public static function validarRespuesta($preguntaId, $respuesta)
    {
        $preguntas = self::getPreguntasActivas();
        $pregunta = null;
        
        foreach ($preguntas as $p) {
            if ($p['id'] == $preguntaId) {
                $pregunta = $p;
                break;
            }
        }
        
        if (!$pregunta) {
            return ['correcta' => false, 'explicacion' => 'Pregunta no encontrada'];
        }
        
        $correcta = false;
        
        switch ($pregunta['tipo']) {
            case self::TIPO_RELACIONAR:
                if (is_array($respuesta) && is_array($pregunta['respuesta_correcta'])) {
                    $correcta = true;
                    foreach ($pregunta['respuesta_correcta'] as $concepto => $respuestaCorrecta) {
                        if (!isset($respuesta[$concepto]) || $respuesta[$concepto] !== $respuestaCorrecta) {
                            $correcta = false;
                            break;
                        }
                    }
                }
                break;
                
            case self::TIPO_SELECCION_MULTIPLE:
                if (is_array($respuesta) && is_array($pregunta['respuesta_correcta'])) {
                    sort($respuesta);
                    sort($pregunta['respuesta_correcta']);
                    $correcta = $respuesta === $pregunta['respuesta_correcta'];
                }
                break;
                
            case self::TIPO_COMPLETAR:
                $correcta = strtolower(trim($respuesta)) === strtolower(trim($pregunta['respuesta_correcta']));
                break;
                
            default:
                $correcta = $respuesta === $pregunta['respuesta_correcta'];
                break;
        }
        
        return [
            'correcta' => $correcta,
            'respuesta_correcta' => $pregunta['respuesta_correcta'],
            'explicacion' => $pregunta['explicacion']
        ];
    }

    /**
     * Calcula el puntaje del examen
     * @param array $respuestas
     * @return array
     */
    public static function calcularPuntaje($respuestas)
    {
        $preguntas = self::getPreguntasActivas();
        $totalPreguntas = count($preguntas);
        $correctas = 0;
        $resultados = [];
        
        foreach ($preguntas as $pregunta) {
            $respuesta = $respuestas[$pregunta['id']] ?? null;
            $validacion = self::validarRespuesta($pregunta['id'], $respuesta);
            
            if ($validacion['correcta']) {
                $correctas++;
            }
            
            $resultados[] = [
                'pregunta' => $pregunta,
                'respuesta_usuario' => $respuesta,
                'correcta' => $validacion['correcta'],
                'respuesta_correcta' => $validacion['respuesta_correcta'],
                'explicacion' => $validacion['explicacion']
            ];
        }
        
        $porcentaje = ($correctas / $totalPreguntas) * 100;
        
        return [
            'total_preguntas' => $totalPreguntas,
            'correctas' => $correctas,
            'incorrectas' => $totalPreguntas - $correctas,
            'porcentaje' => round($porcentaje, 2),
            'resultados' => $resultados
        ];
    }
}
