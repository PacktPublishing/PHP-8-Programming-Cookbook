<?php

use Cookbook\Chapter12\CsrfToken;

include __DIR__ . '/../../vendor/autoload.php';

$csrf = new CsrfToken();
$token = $csrf->getToken();
?>
<form method="post" action="Submit.php">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="text" name="message" required>
    <button type="submit">Submit</button>
</form>