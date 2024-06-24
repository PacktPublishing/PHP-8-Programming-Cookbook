<?php
$getChaps = function () {
    $list = glob('/repo/chapter*');
    $html = '<ul>';
    foreach ($list as $item) {
        $html .= '<li>';
        $html .= '<a href="/' . $item . '/">' . $item . '</a>';
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
};
$getInfo = function () {
    ob_start();
    phpinfo();
    $info = ob_get_contents();
    ob_end_clean();
    return $info;
};
$html = '<table>';
$html .= '<tr>';
$html .= '<td>';
$html .= $getChaps();
$html .= '</td>';
$html .= '<td>';
$html .= $getInfo();
$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';
echo $html;
