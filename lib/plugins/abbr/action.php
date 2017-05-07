<?php
/**
 * DokuWiki Plugin Abbr (Action component)
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

class action_plugin_abbr extends DokuWiki_Action_Plugin {

    /**
     * register the eventhandlers
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'abbr_button', array ());
    }

    /**
     * Inserts a toolbar button
     */
    public function abbr_button(Doku_Event $event, $param) {
        $event->data[] = array (
            'type' => 'picker',
            'class'  => 'pk_hl',
            'title' => $this->getLang('abbr_toolbar_title'),
            'icon' => DOKU_REL.'lib/plugins/abbr/images/abbr-picker.png',
            'list' => array(
                array( // Type 1
                    'type'   => 'format',
                    'title'  => $this->getLang('abbr_type1'),
                    'sample' => $this->getLang('abbr_type1_sample'),
                    'icon'   => DOKU_REL.'lib/plugins/abbr/images/abbr-type1.png',
                    'open'   => '<abbr>',
                    'close'  => '</abbr>',
                ),
                array( // Type 2
                    'type'   => 'format',
                    'title'  => $this->getLang('abbr_type2'),
                    'sample' => $this->getLang('abbr_type2_sample'),
                    'icon'   => DOKU_REL.'lib/plugins/abbr/images/abbr-type2.png',
                    'open'   => '<abbr>',
                    'close'  => '</abbr>',
                ),
                array( // Type 0
                    'type'   => 'format',
                    'title'  => $this->getLang('abbr_type0'),
                    'sample' => $this->getLang('abbr_type0_sample'),
                    'icon'   => DOKU_REL.'lib/plugins/abbr/images/abbr-type0.png',
                    'open'   => '<abbr title="">',
                    'close'  => '</abbr>',
                )
            )
        );
    }
}
