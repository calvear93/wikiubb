<?php
/**
 * DokuWiki Plugin Abbr: abbr tag for abbreviation in Wiki text
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Satoshi Sahara <sahara.satoshi@gmail.com>
 *
 * SYNTAX:
 *       Type 1: <abbr>whole phrase (shortened word)</abbr>
 *       Type 2: <abbr>shortened word [whole phrase]</abbr>
 *
 * OUTPUT:
 *       <abbr title="whole phrase">shortened word</abbr>
 */

if(!defined('DOKU_INC')) die();

class syntax_plugin_abbr_abbreviation extends DokuWiki_Syntax_Plugin {

    protected $special_pattern = '<abbr\b(?:\s+short)?>.*?</abbr>';

    public function getType() { return 'formatting'; }
    public function getSort() { return 65; }

    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern($this->special_pattern,$mode,substr(get_class($this), 7));
    }

   /**
    * Handle the match
    */
    public function handle($match, $state, $pos, Doku_Handler $handler) {

        $match = substr($match, 6,-7); // drop markup
        if (preg_match("/^short>/", $match)) {
            $shortonly = true;
            $match = substr($match, 6);
        } else {
            $shortonly = false;
        }

        if (preg_match("/(^.*)（(((?>[^（）]+)|(?R))*)）$/u", $match, $matches)) {
            // Type 1-Japanese 末尾に置いた「全角カッコ内」で省略語を指定（日本語向け）
            $shortened = $matches[2];
            $phrase = $matches[1];
        } elseif (preg_match("/(^.*)\((((?>[^\(\)]+)|(?R))*)\)$/", $match, $matches)) {
            // Type 1: shortened word will found in tailing ()
            // ex. <abbr>HyperText Markup Language (HTML)</abbr>
            $shortened = $matches[2];
            $phrase = rtrim($matches[1]);
        } elseif (preg_match("/(^.*)\[(((?>[^\[\]]+)|(?R))*)\]$/", $match, $matches)) {
            // Type 2: whole phrase will found in tailing []
            // ex. <abbr>HTML [HyperText Markup Language]</abbr>
            $shortened = rtrim($matches[1]);
            $phrase = $matches[2];
        } elseif (strpos($match,'|') !== false) {
            // Type 3: (experimental)
            // ex. <abbr>HTML|HyperText Markup Language</abbr>
            list($shortened, $phrase) = explode('|',$match,2);
            $shortened = trim($shortened);
            $phrase = trim($phrase);
        } else {
            //msg('shortend word not found in "'.$match.'"' ,2);
            $shortonly = true;
            $shortened = trim($match);
            $phrase = $shortened;
        }
        return array($state, $shortonly, $shortened, $phrase);
    }

   /**
    * Create output
    */
    public function render($format, Doku_Renderer $renderer, $data) {

        if ($format != 'xhtml') return false;

        list($state, $shortonly, $shortened, $phrase) = $data;
        if ($shortonly) {
            //$html = '<abbr>'.hsc($shortened).'</abbr>';
            $html = hsc($shortened);
        } else {
            $html = '<abbr';
            $html.= (empty($phrase)) ? '>' : ' title="'.hsc($phrase).'">';
            $html.= hsc($shortened).'</abbr>';
        }
        $renderer->doc .= $html;
        return true;
    }
}
