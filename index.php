<?php
include __DIR__ . '/src/Chapter01/View/ChapInfo.php';
use Cookbook\Chapter01\View\ChapInfo;
$html = '<style>no_bullet { list-style-type: none; }</style>';
$html .= '<table>';
$html .= '<tr>';
$html .= '<td>';
$html .= '<br /><a href="/adminer.php">DB Admin</a>';
$html .= ChapInfo::getChaps(__DIR__);
$html .= '</td>';
$html .= '<td>';
$html .= '<h1>';
$html .= '<img src="/images/logo.jpg" style="float:left;margin-bottom:10px;"/>&nbsp;';
$html .= 'PHP 8 Programming Cookbook';
$html .= '</h1>';
$html .= '<hr />';
if (isset($_GET['chap'])) {
	$chap = trim(strip_tags($_GET['chap']));
	$html .= ChapInfo::getChapFiles(__DIR__, $chap);
} else {
	$html .= ChapInfo::getInfo(__DIR__);
}
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
