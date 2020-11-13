<?php

use Carbon\Carbon;

function completeFormatedDate($date, $weekday = true, $day = true, $year = true)
{
	date_default_timezone_set('America/Sao_Paulo');
	setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
	setlocale(LC_TIME, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
	$format = '';
	if ($weekday) {
		$format .= "%A ";
		if ($day || $year) $format .= " , ";
	}
	if ($day) {
		$format .= "%d de %B ";
		if ($year) $format .= "de ";
	}
	if ($year) $format .= "%Y";
	return  Carbon::create($date)->formatLocalized($format);
}