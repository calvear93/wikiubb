<?php
/**
 * Options for the rater plugin
 */

$conf['voting_restriction'] = true;              // restrict ip address voting (true or false)
$conf['vote_qty']           = 1;                  // how many times an ip address can vote
$conf['already_rated_msg']  ="Ya ha valorado este item. Sólo está permitido %s voto(s).";
$conf['not_selected_msg']   ="No ha seleccionado una valoración todavía.";
$conf['thankyou_msg']       ="Gracias por valorar este item.";
$conf['generic_text']       ="este item";        // generic item text
$conf['eol_char']           ="\n";               // to separate the records
