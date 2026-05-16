<?php
declare(strict_types=1);

use Cookbook\Chapter12\Password\PasswordOptions;
use Cookbook\Chapter12\Password\PasswordGenerator;

include __DIR__ . '/../../vendor/autoload.php';

// Minimal options from query string (plain & simple)
$length      = isset($_GET['len']) ? (int) $_GET['len'] : 20;
$useSymbols  = isset($_GET['symbols']);
$noAmbiguous = isset($_GET['no_ambiguous']);

try {
    $options = (new PasswordOptions())
        ->setLength($length)
        ->setUseSymbols($useSymbols)
        ->setNoAmbiguous($noAmbiguous);

    $generator = (new PasswordGenerator())->applyOptions($options);
    $password  = $generator->generate();
    $error     = null;
} catch (Throwable $e) {
    $password = '';
    $error    = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Random Password</title>
</head>
<body>
<h1>Random Password</h1>

<?php if ($error !== null): ?>
    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php else: ?>
    <p><strong><?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?></strong></p>
<?php endif; ?>

<form method="get">
    <label>
        Length:
        <input type="number" name="len" min="8" max="128" value="<?php echo (int) $length; ?>">
    </label>
    <br>
    <label>
        <input type="checkbox" name="symbols" <?php echo $useSymbols ? 'checked' : ''; ?>>
        Include symbols
    </label>
    <br>
    <label>
        <input type="checkbox" name="no_ambiguous" <?php echo $noAmbiguous ? 'checked' : ''; ?>>
        No ambiguous characters
    </label>
    <br>
    <button type="submit">Generate</button>
</form>

<p>Reload or press “Generate” to get a new password.</p>
</body>
</html>
