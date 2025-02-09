<?php
include __DIR__ . '/ch03_developing_functions_namespace_alpha.php';
include __DIR__ . '/ch03_developing_functions_namespace_beta.php';
echo \Alpha\someFunction();
echo \Beta\someFunction();
