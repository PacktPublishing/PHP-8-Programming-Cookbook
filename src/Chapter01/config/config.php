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
    ]
    'conversions' => [
    
        // From GPT 5.2:
<?php

$rules = [

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
     * PHP 8.1: strftime()/gmstrftime() deprecated.
     * A safe auto-rewrite is NOT generally possible because format tokens differ.
     * So we annotate it.
     *
     * Migration ref: https://www.php.net/manual/en/migration81.deprecated.php
     */
    '~\b(?P<fn>gmstrftime|strftime)\s*\(~i'
        => function (array $m): string {
            return "/* TODO(PHP8.1+): {$m['fn']}() is deprecated; consider DateTime::format() or IntlDateFormatter. */\n" . $m[0];
        },

    /**
     * PHP 8.2: utf8_encode()/utf8_decode() deprecated.
     * We can suggest mb_convert_encoding, but automatic conversion is risky:
     * - utf8_encode() assumes ISO-8859-1 input -> UTF-8 output.
     * - utf8_decode() assumes UTF-8 input -> ISO-8859-1 output.
     *
     * Migration ref: https://www.php.net/manual/en/migration82.deprecated.php
     */
    '~\butf8_encode\s*\(~i'
        => function (array $m): string {
            return "/* TODO(PHP8.2+): utf8_encode() is deprecated; often: mb_convert_encoding(<expr>, 'UTF-8', 'ISO-8859-1'). Verify input encoding. */\n" . $m[0];
        },

    '~\butf8_decode\s*\(~i'
        => function (array $m): string {
            return "/* TODO(PHP8.2+): utf8_decode() is deprecated; often: mb_convert_encoding(<expr>, 'ISO-8859-1', 'UTF-8'). Verify desired output encoding. */\n" . $m[0];
        },

    /**
     * PHP 8.2: Dynamic properties deprecated.
     * This is not a simple syntax change; a regex rewrite is not safe.
     * We flag common patterns like: $this->something = ...
     * inside a class context we *can't* reliably detect with regex alone,
     * so this only adds an annotation when we see "$this-><name> =" generally.
     *
     * Migration ref: https://www.php.net/manual/en/migration82.deprecated.php
     */
    '~(?<!->)\$this->(?P<prop>[A-Za-z_\x80-\xff][A-Za-z0-9_\x80-\xff]*)\s*=\s*~'
        => function (array $m): string {
            return "/* TODO(PHP8.2+): dynamic property '{$m['prop']}' deprecated; declare the property or add #[AllowDynamicProperties] if appropriate. */\n"
                 . '$this->' . $m['prop'] . ' = ';
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
        // PHP 8.0: each() function removed [[0]](#__0) [[1]](#__1)
        '/\beach\s*\(\s*(\$\w+)\s*\)/' => function($matches) {
            return 'current(' . $matches[1] . ') !== false ? [key(' . $matches[1] . '), current(' . $matches[1] . ')] : false; next(' . $matches[1] . ')';
        },
        
        // PHP 8.0: create_function() removed [[1]](#__1)
        '/\bcreate_function\s*\(\s*[\'"]([^\'"]*)[\'"]\s*,\s*[\'"]([^\'"]*)[\'"]\s*\)/' => function($matches) {
            $params = $matches[1];
            $body = $matches[2];
            return "function({$params}) { {$body} }";
        },
        
        // PHP 8.0: money_format() removed [[3]](#__3)
        '/\bmoney_format\s*\(\s*([^,]+),\s*([^)]+)\s*\)/' => function($matches) {
            return 'number_format(' . $matches[2] . ', 2)';
        },
        
        // PHP 8.0: Implode with wrong parameter order (deprecated since 7.4, removed in 8.0) [[0]](#__0) [[2]](#__2)
        '/\bimplode\s*\(\s*(\$\w+|array\([^)]*\)|\[[^\]]*\])\s*,\s*[\'"]([^\'"]*)[\'"]\s*\)/' => function($matches) {
            return 'implode(\'' . $matches[2] . '\', ' . $matches[1] . ')';
        },
        
        // PHP 8.0: Required parameters after optional ones [[1]](#__1)
        '/function\s+(\w+)\s*\([^)]*\$\w+\s*=\s*[^,)]+\s*,\s*\$\w+(?!\s*=)[^)]*\)/' => function($matches) {
            // This would need manual review as parameter reordering is context-dependent
            return '/* WARNING: Required parameter after optional - needs manual fix */ ' . $matches[0];
        },
        
        // PHP 8.0: libxml_disable_entity_loader() deprecated [[1]](#__1)
        '/\blibxml_disable_entity_loader\s*\([^)]*\)/' => function($matches) {
            return '/* libxml_disable_entity_loader() deprecated - no longer needed in PHP 8.0+ */';
        },
        
        // PHP 8.1: Passing null to non-nullable internal function parameters [[2]](#__2)
        '/\bstrlen\s*\(\s*null\s*\)/' => function($matches) {
            return 'strlen(\'\')';
        },
        
        // PHP 8.1: Serializable interface deprecated [[2]](#__2)
        '/class\s+(\w+)\s+implements\s+([^{]*\b)Serializable\b/' => function($matches) {
            $className = $matches[1];
            $otherInterfaces = trim(str_replace('Serializable', '', $matches[2]), ', ');
            $implements = $otherInterfaces ? ' implements ' . $otherInterfaces : '';
            return "class {$className}{$implements} {\n    public function __serialize(): array { /* Implement */ }\n    public function __unserialize(array \$data): void { /* Implement */ }";
        },
        
        // PHP 8.1: MySQLi default error mode changed [[2]](#__2)
        '/mysqli_report\s*\(\s*MYSQLI_REPORT_OFF\s*\)/' => function($matches) {
            return 'mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)';
        },
        
        // PHP 8.2: Dynamic properties deprecated [[2]](#__2)
        '/class\s+(\w+)(?!\s+extends|\s+implements)[^{]*{/' => function($matches) {
            return '#[\AllowDynamicProperties]' . "\n" . $matches[0];
        },
        
        // PHP 8.2: utf8_encode() and utf8_decode() deprecated [[2]](#__2)
        '/\butf8_encode\s*\(([^)]+)\)/' => function($matches) {
            return 'mb_convert_encoding(' . $matches[1] . ', \'UTF-8\', \'ISO-8859-1\')';
        },
        
        '/\butf8_decode\s*\(([^)]+)\)/' => function($matches) {
            return 'mb_convert_encoding(' . $matches[1] . ', \'ISO-8859-1\', \'UTF-8\')';
        },
        
        // PHP 8.2: ${} string interpolation deprecated [[2]](#__2)
        '/\$\{([^}]+)\}/' => function($matches) {
            return '{$' . $matches[1] . '}';
        },
        
        // PHP 8.3: get_class() without arguments deprecated [[2]](#__2)
        '/\bget_class\s*\(\s*\)/' => function($matches) {
            return 'get_class($this)';
        },
        
        // PHP 8.3: uniqid() without more_entropy deprecated [[2]](#__2)
        '/\buniqid\s*\(\s*\)/' => function($matches) {
            return 'uniqid(\'\', true)';
        },
        
        // PHP 8.4: Implicit nullable parameter declarations deprecated [[2]](#__2)
        '/function\s+\w+\s*\([^)]*\$(\w+)\s*=\s*null[^)]*\)/' => function($matches) {
            // This needs context-aware replacement
            return '/* CHECK: Implicit nullable - add ?type if needed */ ' . $matches[0];
        },
        
        // PHP 8.0: @ error suppression with critical errors [[1]](#__1)
        '/@\s*(include|require|include_once|require_once)\s*\(/' => function($matches) {
            return $matches[1] . '(';
        },
        
        // PHP 8.0: mb_ereg_replace() with invalid pattern [[1]](#__1)
        '/\bmb_ereg_replace\s*\(\s*[\'"]([^\'"]*)[\'"]\s*,/' => function($matches) {
            return 'mb_ereg_replace(\'' . addslashes($matches[1]) . '\',';
        },
        
        // PHP 8.1: mysqli_fetch_object() with constructor args deprecated [[2]](#__2)
        '/\bmysqli_fetch_object\s*\([^,]+,\s*[\'"](\w+)[\'"]\s*,\s*array\s*\([^)]*\)\s*\)/' => function($matches) {
            return 'mysqli_fetch_object($result, \'' . $matches[1] . '\')';
        },
        
        // PHP 8.2: Partially supported callables deprecated [[2]](#__2)
        '/array\s*\(\s*[\'"](\w+)[\'"]\s*,\s*[\'"](\w+)[\'"]\s*\)/' => function($matches) {
            $class = $matches[1];
            $method = $matches[2];
            if ($class === 'self' || $class === 'parent' || $class === 'static') {
                return $class . '::' . $method . '(...)';
            }
            return $matches[0];
        },
        
        // PHP 8.3: Calling ReflectionProperty methods on dynamic properties deprecated [[2]](#__2)
        '/\$reflection->getValue\(\$(\w+)\)/' => function($matches) {
            return '$' . $matches[1] . '->{$propertyName} ?? null';
        },
        
        // PHP 8.0: assert() with string argument [[1]](#__1)
        '/\bassert\s*\(\s*[\'"]([^\'"]*)[\'"]\s*\)/' => function($matches) {
            return 'assert(' . $matches[1] . ')';
        },
        
        // PHP 8.1: strftime() and gmstrftime() deprecated [[2]](#__2)
        '/\b(strftime|gmstrftime)\s*\(([^)]+)\)/' => function($matches) {
            return '(new DateTime())->format(' . $matches[2] . ')';
        },
        
        // PHP 8.2: #[\AllowDynamicProperties] for stdClass [[2]](#__2)
        '/new\s+stdClass\s*\(\s*\)/' => function($matches) {
            return 'new stdClass()';
        },
        
        // PHP 8.4: E_STRICT constant deprecated [[2]](#__2)
        '/\bE_STRICT\b/' => function($matches) {
            return '/* E_STRICT deprecated - remove or use E_ALL */';
        }
    ]
];
/*
 * // Usage example:
function migrate_php_code($code) {
    global $migration_rules;
    
    foreach ($migration_rules as $pattern => $replacer) {
        $code = preg_replace_callback($pattern, $replacer, $code);
    }
    
    return $code;
}

// Example usage:
$old_code = '
$result = each($array);
$func = create_function(\'$a,$b\', \'return $a + $b;\');
$price = money_format(\'%i\', $number);
$string = implode($array, \',\');
echo strlen(null);
class MyClass implements Serializable {}
';

$new_code = migrate_php_code($old_code);
echo $new_code;
*/
