<?php
/**
 * backgroundcolor Plugin: Allows user-defined font colors
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     modified by ThorstenStratmann <thorsten.stratmann@web.de>
 * @link       https://www.dokuwiki.org/plugin:fontcolor
 * @version    3.1
 */

if(!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_backgroundcolor extends DokuWiki_Syntax_Plugin {
    /**
     * What kind of syntax are we?
     *
     * @return string
     */
    public function getType() {
        return 'formatting';
    }

    /**
     * What kind of syntax do we allow (optional)
     *
     * @return array
     */
    public function getAllowedTypes() {
        return array('formatting', 'substition', 'disabled');
    }

    /**
     * What about paragraphs? (optional)
     *
     * @return string
     */
    public function getPType() {
        return 'normal';
    }

    /**
     * Where to sort in?
     *
     * @return int
     */
    public function getSort() {
        return 90;
    }

    /**
     * Connect pattern to lexer
     *
     * @param string $mode
     */
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('<background-color.*?>(?=.*?</background-color>)', $mode, 'plugin_backgroundcolor');
    }

    public function postConnect() {
        $this->Lexer->addExitPattern('</background-color>', 'plugin_backgroundcolor');
    }

    /**
     * override default accepts() method to allow nesting - ie, to get the plugin accepts its own entry syntax
     *
     * @param string $mode
     * @return bool
     */
    public function accepts($mode) {
        if($mode == 'plugin_backgroundcolor') return true;
        return parent::accepts($mode);
    }

    /**
     * Handle the match
     *
     * @param   string $match The text matched by the patterns
     * @param   int $state The lexer state for the match
     * @param   int $pos The character position of the matched text
     * @param   Doku_Handler $handler The Doku_Handler object
     * @return  array Return an array with all data you want to use in render
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        switch($state) {
            case DOKU_LEXER_ENTER :
                $color = trim(substr($match, 18, -1)); // get the color
                $color = $this->_color2hexdec($color);
                if($color) {
                    return array($state, $color);
                }
                break;
            case DOKU_LEXER_UNMATCHED :
                $handler->_addCall('cdata', array($match), $pos);
                return false;
            case DOKU_LEXER_EXIT :
                break;
        }
        return array($state, "#ffff00");
    }

    /**
     *  Create output
     *
     * @param   $mode   string        output format being rendered
     * @param   $renderer Doku_Renderer the current renderer object
     * @param   $data     array         data created by handler()
     * @return  boolean                 rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if($mode == 'xhtml') {
            /** @var $renderer Doku_Renderer_xhtml */
            list($state, $color) = $data;
            switch($state) {
                case DOKU_LEXER_ENTER :
                    $renderer->doc .= "<span style=\"background-color: $color\">";
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= "</span>";
                    break;
            }
            return true;
        }
        return false;
    }

    /**
     * Returns #hexdec color code, or false
     *
     * @param string $color
     * @return bool|string
     */
    protected function _color2hexdec($color) {
        $less = new lessc();
        $less->importDir[] = DOKU_INC;

        $css = '.test { color: spin('.$color.', 0); }';  //less try to spin all colors, and output them as hexdec
        try {
            $parsedcss =  $less->compile($css);
        } catch(Exception $e) {
            return false;
        }
        $hexdec = substr($parsedcss, 17, -4);
        return $hexdec;
    }

    /**
     * validate color value $c
     * this is cut price validation - only to ensure the basic format is
     * correct and there is nothing harmful
     * three basic formats  "colorname", "#fff[fff]", "rgb(255[%],255[%],255[%])"
     *
     * @param string $c
     * @return int
     */
    protected function _isValid($c) {

        $c = trim($c);

        $pattern = "/
            (^[a-zA-Z]+$)|                                #colorname - not verified
            (^\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$)|        #colorvalue
            (^rgb\(([0-9]{1,3}%?,){2}[0-9]{1,3}%?\)$)     #rgb triplet
            /x";

        return (preg_match($pattern, $c));
    }
}

//Setup VIM: ex: et ts=4 sw=4 enc=utf-8 :