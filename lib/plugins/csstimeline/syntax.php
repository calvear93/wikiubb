<?php
/**
 * csstimeline Plugin - Create a CSS-only timeline
 *
 * Usage:
 *
 * <csstimeline>
 * <entry>
 * date: 04.12.2014
 * title: My first timeline entry
 * description: Within the description, you can even use Wiki markup
 * </entry>
 * <entry>
 * date: 06.01.2015
 * title: My second timeline entry
 * description: This one is rendered at the other side of the timeline
 * </entry>
 * </csstimeline>
 *
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Böhler <dev@aboehler.at>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_csstimeline extends DokuWiki_Syntax_Plugin {

    protected $_helper = null;
  
    // Load the helper plugin
    public function syntax_plugin_csstimeline() { 
        $this->_helper =& plugin_load('helper', 'csstimeline');      
    }


    function getType() { 
        return 'substition'; 
    }
    
    function getPType() { 
        return 'normal'; 
    }
    
    function getAllowedTypes() { 
        return array('container','substition','protected','disabled','paragraphs','formatting'); 
    }
    
    function getSort() { 
        return 777; 
    }
    
    function connectTo($mode) { 
        $this->Lexer->addSpecialPattern($this->_helper->specialPattern,$mode,'plugin_csstimeline');
    }

   /**
    * Handle the match. Use either the standard linking mechanism or, when enabled,
    * pass the title through the parser
    */
    function handle($match, $state, $pos, Doku_Handler $handler) {
        $data = $this->_helper->handleMatch($match);

        return $data;
    }

   /**
    * Create output. This is largely based on the internal linking mechanism.
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        if (empty($data)) return false;
        

        if($mode == 'xhtml') {
            $direction = 'r';
            $renderer->doc .= '<div class="clearer"></div><div><ul class="timeline">';
            foreach($data['entries'] as $entry)
            {
                $renderer->doc .= '<li>';
                $renderer->doc .= '<div class="direction-'.$direction.'">';
                $renderer->doc .= '<div class="flag-wrapper">';
                $renderer->doc .= '<span class="flag">'.$entry['title'].'</span>';
                $renderer->doc .= '<span class="time-wrapper"><span class="time">'.$entry['date'].'</span></span>';
                $renderer->doc .= '</div>';
                $renderer->doc .= '<div class="desc">'.$entry['description'].'</div>';
                $renderer->doc .= '</li>';
                if($direction === 'r')
                    $direction = 'l';
                else
                    $direction = 'r';
            }
            $renderer->doc .= '</ul></div>';
            
        }
        return false;
    }
}
