<?php
namespace Library;
include __DIR__ . '/ch03_match_func_lib.php';       // loads functions
if (!empty($_POST)) {
    // load functions
    // determine format
    $accept = strtolower(trim(strip_tags($_POST['accept'] ?? $_SERVER['HTTP_ACCEPT'] ?? ACCEPT_T)));
    // sanitize data
    $data = array_map(function ($val) { return trim(strip_tags($val)); }, ($_POST ?? []));
    // callback tree
    echo match (TRUE) {
        str_contains($accept, ACCEPT_X) => $x($data),
        str_contains($accept, ACCEPT_J) => $j($data),
        str_contains($accept, ACCEPT_H) => $h($data),
        str_contains($accept, ACCEPT_T) => $t($data),
        default => 'No post data'
    };
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Chapter 3 -- Match</title>
<meta name="generator" content="Geany 2.0" />
</head>
<body>
Enter the following:
<form method="post" action="/src/Chapter03/ch03_match_runner.php">
<table>
<tr><th>First Name</th><td><input type="text" name="first" /></td></tr>
<tr><th>Last Name</th><td><input type="text" name="last" /></td></tr>
<tr><th>Email Address</th><td><input type="text" name="email" /></td></tr>
<tr>
    <th>Output Format:</th>
    <td>
        <select name="accept">
            <option value="<?= ACCEPT_X ?>">XML</option>
            <option value="<?= ACCEPT_J ?>">JSON</option>
            <option value="<?= ACCEPT_H ?>">HTML</option>
            <option value="<?= ACCEPT_T ?>">Text</option>
        </select>
    </td>
</tr>
<tr><th>&nbsp;</th><td><input type="submit" /></td></tr>
</table>
</form>
</body>
</html>
