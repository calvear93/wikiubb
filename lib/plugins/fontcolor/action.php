<?php
/**
 * Action Component for the FontColor plugin
 */

if(!defined('DOKU_INC')) die();

/**
 * Action Component for the FontColor plugin
 */
class action_plugin_fontcolor extends DokuWiki_Action_Plugin {
    /**
     * register the event handlers
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'toolbarEventHandler', array());
    }

    /**
     * Adds FontColor toolbar button
     *
     * @param Doku_Event $event
     * @param mixed $param
     */
    public function toolbarEventHandler(Doku_Event $event, $param) {
        $colors = array(
            'Amarillo'        => '#FFC107',
            'Rojo'           => '#F44336',
            'Rojo Oscuro'           => '#D32F2F',
            'Naranjo'        => '#ffa500',
            'Naranjo Profundo' => '#FF5722',
            'Salmon'        => '#fa8072',
            'Rosa'          => '#ffc0cb',
            'Ciruela'          => '#dda0dd',
            'Púrpura'        => '#9C27B0',
            'Púrpura Profundo'        => '#673AB7',
            'Fucsia'       => '#ff00ff',
            'Rosa Fuerte'          => '#E91E63',
            'Plata'        => '#c0c0c0',
            'Cielo'          => '#00ffff',
            'Verde Azulado'          => '#009688',
            'Indigo'          => '#3F51B5',
            'Indigo Pálido'    => '#536DFE',
            'Azul Cielo'      => '#87ceeb',
            'Turquesa'    => '#7fffd4',
            'Cian'    => '#00BCD4',
            'Verde Pálido'    => '#98fb98',
            'Lima'          => '#00ff00',
            'Lima Oscuro'          => '#CDDC39',
            'Verde'         => '#4CAF50',
            'Verde Oscuro'         => '#388E3C',
            'Verde Pálido'         => '#8BC34A',
            'Oliva'         => '#808000',
            'Rojo Indio'    => '#cd5c5c',
            'Caqui'         => '#f0e68c',
            'Azul Pálido'   => '#b0e0e6',
            'Café Arena'   => '#f4a460',
            'Azul Acero'    => '#4682b4',
            'Cardo'       => '#d8bfd8',
            'Verde Amarillo'  => '#9acd32',
            'Violeta Oscuro'   => '#9400d3',
            'Granate'        => '#800000'
        );

        $color = array(
            'type' => 'picker',
            'title' => 'Color de la Fuente',
            'icon' => '../../plugins/fontcolor/images/font.png',
            'list' => array()
        );

        foreach($colors as $colorName => $colorValue) {
            $color['list'] [] = array(
                'type' => 'format',
                'title' => $colorName,
                'icon' => '../../plugins/fontcolor/images/color-icon.php?color='
                    . substr($colorValue, 1),
                'open' => '<color ' . $colorValue . '>',
                'close' => '</color>'
            );
        }

        $background = array(
            'type' => 'picker',
            'title' => 'Color de Fondo de la Fuente',
            'icon' => '../../plugins/backgroundcolor/paintbrush.png',
            'list' => array()
        );

        foreach($colors as $colorName => $colorValue) {
            $background['list'] [] = array(
                'type' => 'format',
                'title' => $colorName,
                'icon' => '../../plugins/fontcolor/images/color-icon.php?color='
                    . substr($colorValue, 1),
                'open' => '<background-color ' . $colorValue . '>',
                'close' => '</background-color>'
            );
        }

        $button = array(
            'type' => 'picker',
            'title' => 'Color Texto',
            'icon' => '../../plugins/fontcolor/images/color.png',
            'class'  => 'pk_hl',
            'list' => array($color, $background)
        );

        $event->data[] = $button;
        //$event->data[] = $color;
        //$event->data[] = $background;
    }
}
