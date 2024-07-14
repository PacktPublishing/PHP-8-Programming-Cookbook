<?php
$getChaps = function () {
    $style = 'list-style-type: none;';
    $list = glob('/repo/chapter*');
    foreach ($list as $item) {
        $html .= '<br />';
        $html .= '<a href="/' . $item . '/">' . basename($item) . '</a>';
    }
    return $html;
};
$getInfo = function () {
    ob_start();
    phpinfo();
    $info = ob_get_contents();
    ob_end_clean();
    return $info;
};
$html = '<style>no_bullet { list-style-type: none; }</style>';
$html .= '<table>';
$html .= '<tr>';
$html .= '<td>';
$html .= '<br /><a href="/adminer.php">DB Admin</a>';
$html .= $getChaps();
$html .= '</td>';
$html .= '<td>';
$html .= '<h1>';
$html .= '<img src="/images/logo.jpg" style="float:left;margin-bottom:10px;"/>&nbsp;';
$html .= 'PHP 8 Programming Cookbook';
$html .= '</h1>';
$html .= '<hr />';
$html .= $getInfo();
$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>PHP 8 Programming Cookbook</title>
<link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
<?= $html; ?>
</body>
</html>
