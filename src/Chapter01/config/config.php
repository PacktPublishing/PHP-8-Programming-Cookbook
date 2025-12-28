<?php
use Cookbook\Chapter01\Converter\Convert;
return [ 
    // location of Rules classes
    Convert::RULES_KEY => [
        'Cookbook\Chapter01\Converter\Rules\ClassConstructor',
        'Cookbook\Chapter01\Converter\Rules\MagicAutoload',
        'Cookbook\Chapter01\Converter\Rules\Define3rdArg',
    ],
    // additional simple conversions
    Convert::CONVERT_KEY => [
        // adds message is match() is defined as a function
        '/(public|protected|private) function match\b/' =>  function ($match) {
            return Convert::PROBLEM . '"match" is now a keyword and cannot be used to define a function'
                . Convert::LF_REPLACE . "\t"
                . $match[0];
        },
        
        // adds message is mixed() is defined as a function
        '/(public|protected|private) function mixed\b/' =>  function ($match) {
            return Convert::PROBLEM . '"mixed" is now a keyword and cannot be used to define a function'
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
        
        /**
         * PHP 8.0: Curly brace string/array offset access removed.
         * Examples:
         *   $s{0}     -> $s[0]
         *   $arr{'k'} -> $arr['k']
         *
         * Migration ref: https://www.php.net/manual/en/migration80.php
         */
        '~(?P<head>\$[A-Za-z_\x80-\xff][A-Za-z0-9_\x80-\xff]*(?:->\w+|\[[^\]]*])*)\s*\{\s*(?P<idx>[^}]+)\s*\}~'
        => function (array $m): string {
            return $m['head'] . '[' . $m['idx'] . ']';
        },
        
        /**
         * PHP 8.3: Incrementing certain non-numeric strings deprecated.
         * Example patterns are not reliably distinguishable with regex.
         * We can only annotate suspicious ++/-- on string literals.
         *
         * Migration ref: https://www.php.net/manual/en/migration83.deprecated.php
         */
        "~(?P<lit>'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'|\"[^\"\\\\]*(?:\\\\.[^\"\\\\]*)*\")\s*(?P<op>\\+\\+|--)~"
        => function (array $m): string {
            return "/* TODO(PHP8.3+): string {$m['op']} semantics changed/deprecated for some values; verify behavior. */\n"
                 . $m['lit'] . $m['op'];
        },
                    
        // from Anthropic Opus 4.1 Thinking Model:
        // PHP 8.0: each() function removed
        '/\beach\s*\(\s*(\$\w+)\s*\)/' => function($matches) {
            return 'current(' . $matches[1] . ') !== false ? [key(' . $matches[1] . '), current(' . $matches[1] . ')] : false; next(' . $matches[1] . ')';
        },
        
        // PHP 8.0: create_function() removed
        '/\bcreate_function\s*\(\s*[\'"]([^\'"]*)[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]\s*\)/' => function($matches) {
            $params = $matches[1];
            $body = $matches[2];
            return "function({$params}) { {$body} }";
        },
        
        // PHP 8.0: money_format() removed
        '/\bmoney_format\s*\(\s*([^,]+),\s*([^)]+)\s*\)/' => function($matches) {
            return 'number_format(' . $matches[2] . ', 2)';
        },
        
        // PHP 8.0: Implode with wrong parameter order (deprecated since 7.4, removed in 8.0)
        '/\bimplode\s*\(\s*(\$\w+|array\([^)]*\)|\[[^\]]*\])\s*,\s*[\'"]([^\'"]*)[\'"]\s*\)/' => function($matches) {
            return 'implode(\'' . $matches[2] . '\', ' . $matches[1] . ')';
        },
        
        // PHP 8.0: Required parameters after optional ones
        '/function\s+(\w+)\s*\([^)]*\$\w+\s*=\s*[^,)]+\s*,\s*\$\w+(?!\s*=)[^)]*\)/' => function($matches) {
            // This would need manual review as parameter reordering is context-dependent
            return '/* WARNING: Required parameter after optional - needs manual fix */ ' . $matches[0];
        },
        
        // PHP 8.0: libxml_disable_entity_loader() deprecated
        '/\blibxml_disable_entity_loader\s*\([^)]*\)/' => function($matches) {
            return '/* libxml_disable_entity_loader() deprecated - no longer needed in PHP 8.0+ */';
        },
        
        // PHP 8.1: Passing null to non-nullable internal function parameters
        '/\bstrlen\s*\(\s*null\s*\)/' => function($matches) {
            return 'strlen(\'\')';
        },
        
        // PHP 8.1: Serializable interface deprecated
        '/class\s+(\w+)\s+implements\s+([^{]*\b)Serializable\b/' => function($matches) {
            $className = $matches[1];
            $otherInterfaces = trim(str_replace('Serializable', '', $matches[2]), ', ');
            $implements = $otherInterfaces ? ' implements ' . $otherInterfaces : '';
            return "class {$className}{$implements} {\n    public function __serialize(): array { /* Implement */ }\n    public function __unserialize(array \$data): void { /* Implement */ }";
        },
        
        // PHP 8.1: MySQLi default error mode changed
        '/mysqli_report\s*\(\s*MYSQLI_REPORT_OFF\s*\)/' => function($matches) {
            return 'mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)';
        },
        
        // PHP 8.2: Dynamic properties deprecated
        '/class\s+(\w+)(?!\s+extends|\s+implements)[^{]*{/' => function($matches) {
            return '#[\AllowDynamicProperties]' . "\n" . $matches[0];
        },
        
        // PHP 8.2: utf8_encode() and utf8_decode() deprecated
        '/\butf8_encode\s*\(([^)]+)\)/' => function($matches) {
            return 'mb_convert_encoding(' . $matches[1] . ', \'UTF-8\', \'ISO-8859-1\')';
        },
        
        '/\butf8_decode\s*\(([^)]+)\)/' => function($matches) {
            return 'mb_convert_encoding(' . $matches[1] . ', \'ISO-8859-1\', \'UTF-8\')';
        },
        
        // PHP 8.2: ${} string interpolation deprecated
        '/\$\{([^}]+)\}/' => function($matches) {
            return '{$' . $matches[1] . '}';
        },
        
        // PHP 8.3: get_class() without arguments deprecated
        '/\bget_class\s*\(\s*\)/' => function($matches) {
            return 'get_class($this)';
        },
        
        // PHP 8.3: uniqid() without more_entropy deprecated
        '/\buniqid\s*\(\s*\)/' => function($matches) {
            return 'uniqid(\'\', true)';
        },
        
        // PHP 8.4: Implicit nullable parameter declarations deprecated
        '/function\s+\w+\s*\([^)]*\$(\w+)\s*=\s*null[^)]*\)/' => function($matches) {
            // This needs context-aware replacement
            return '/* CHECK: Implicit nullable - add ?type if needed */ ' . $matches[0];
        },
        
        // PHP 8.0: @ error suppression with critical errors
        '/@\s*(include|require|include_once|require_once)\s*\(/' => function($matches) {
            return $matches[1] . '(';
        },
        
        // PHP 8.0: mb_ereg_replace() with invalid pattern
        '/\bmb_ereg_replace\s*\(\s*[\'"]([^\'"]*)[\'"]\s*,/' => function($matches) {
            return 'mb_ereg_replace(\'' . addslashes($matches[1]) . '\',';
        },
        
        // PHP 8.1: mysqli_fetch_object() with constructor args deprecated
        '/\bmysqli_fetch_object\s*\([^,]+,\s*[\'"](\w+)[\'"]\s*,\s*array\s*\([^)]*\)\s*\)/' => function($matches) {
            return 'mysqli_fetch_object($result, \'' . $matches[1] . '\')';
        },
        
        // PHP 8.2: Partially supported callables deprecated
        '/array\s*\(\s*[\'"](\w+)[\'"]\s*,\s*[\'"](\w+)[\'"]\s*\)/' => function($matches) {
            $class = $matches[1];
            $method = $matches[2];
            if ($class === 'self' || $class === 'parent' || $class === 'static') {
                return $class . '::' . $method . '(...)';
            }
            return $matches[0];
        },
        
        // PHP 8.3: Calling ReflectionProperty methods on dynamic properties deprecated
        '/\$reflection->getValue\(\$(\w+)\)/' => function($matches) {
            return '$' . $matches[1] . '->{$propertyName} ?? null';
        },
        
        // PHP 8.0: assert() with string argument
        '/\bassert\s*\(\s*[\'"]([^\'"]*)[\'"]\s*\)/' => function($matches) {
            return 'assert(' . $matches[1] . ')';
        },
        
        // PHP 8.2: #[\AllowDynamicProperties] for stdClass
        '/new\s+stdClass\s*\(\s*\)/' => function($matches) {
            return 'new stdClass()';
        },
        
        // PHP 8.1: strftime() and gmstrftime() deprecated
        '/\b(strftime|gmstrftime)\s*\(([^)]+)\)/' => function($matches) {
            return '(new DateTime())->format(' . $matches[2] . ')';
        },
        
        // PHP 8.4: E_STRICT constant deprecated
        '/\bE_STRICT\b/' => function($matches) {
            return '/* E_STRICT deprecated - remove or use E_ALL */';
        }
    ],
];
