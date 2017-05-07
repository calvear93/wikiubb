<?php
/**
 * @file       divalign2/action.php
 * @brief      Action component for the divalign2 plugin.
 *
 * See common.php for more information.
**/

if(!defined('DOKU_INC')) die();

class action_plugin_poll extends DokuWiki_Action_Plugin {


function register(Doku_Event_Handler $controller) {
    $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'poll_toolbar', array ());
}

function poll_toolbar(Doku_Event $event, $param) {
        $icobase = '../../plugins/poll/';

        $poll = array (
	        'type' => 'format',
	        'title' => 'Cuestionario',
	        'icon' => $icobase.'poll.png',
	        'sample' => 'Título',
	        'open' => '<poll ',
	        'close' => '>\n[Pregunta]\n* [opción 1]\n* [opción 2]\n* [opción 3 (puede insertar más)]\n</poll>',
	    );
        $event->data[] = $poll;
    }

}
