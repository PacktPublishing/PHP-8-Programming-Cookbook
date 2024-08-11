<?php
use Cookbook\Chapter01\Converter\Convert;
return [ Convert::CONVERT_KEY => [
	// adds message is match() is defined as a function
	'/(public|protected|private) function match\b/' =>  function ($match) {
		return sprintf(Convert::PROBLEM, '"match" is now a keyword and cannot be used to define a function')
			. Convert::LF_REPLACE . "\t"
			. $match[0];
	},
	// adds message is mixed() is defined as a function
	'/(public|protected|private) function mixed\b/' =>  function ($match) {
		return sprintf(Convert::PROBLEM, '"mixed" is now a keyword and cannot be used to define a function')
			. Convert::LF_REPLACE . "\t"
			. $match[0];
	},
	// replaces (real) typecast with (float)
	'/\breal\b/' => function ($match) {
		return 'float';
	},
	// returns 'NULL' in place of '(unset) $xyz'
	'/' . Convert::LF_REPLACE . '\(unset\).*?\$.+?\b/' => function ($match) {
		return 'NULL';
	},
]];
