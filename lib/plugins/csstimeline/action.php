<?php
/**
 * @file       divalign2/action.php
 * @brief      Action component for the divalign2 plugin.
 *
 * See common.php for more information.
**/

if(!defined('DOKU_INC')) die();

class action_plugin_csstimeline extends DokuWiki_Action_Plugin {


function register(Doku_Event_Handler $controller) {
    $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'csstimeline_toolbar', array ());
}

function csstimeline_toolbar(Doku_Event $event, $param) {
        $icobase = '../../plugins/csstimeline/';

        $timeline = array (
	        'type' => 'format',
	        'title' => 'Línea de Tiempo',
	        'icon' => $icobase.'timeline.png',
	        'sample' => 'Escriba una descripción.',
	        'open' => '<csstimeline>\n<entry>\ndate: 01.01.2000\ntitle: Título\ndescription: ',
	        'close' => '\n</entry>\n</csstimeline>',
	    );
        $event->data[] = $timeline;
    }

}
