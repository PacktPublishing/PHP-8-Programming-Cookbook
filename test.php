<?php
$patt = '/\bfunction\b.*?__autoload\b/';
$txt = <<<EOT
function __autoload(\$class)
{
	// some code
}
EOT;
$txt = str_replace("\n", ' ---LF--- ', $txt);
preg_match($patt, $txt, $match);
var_dump($match);

