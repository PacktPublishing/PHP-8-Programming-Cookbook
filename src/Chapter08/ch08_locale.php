<?php

session_start();

// On initial load, clear the session and use the language from HTTP_ACCEPT_LANGUAGE
if (!isset($_SESSION['initialized'])) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['initialized'] = true;

    // Detect browser language
    $acceptLanguageHeader = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $browserLocale = Locale::acceptFromHttp($acceptLanguageHeader);
    $supportedLanguages = [
        'en_US' => 'en',
        'en_GB' => 'en',
        'fr_FR' => 'fr',
    ];

    if ($browserLocale && array_key_exists($browserLocale, $supportedLanguages)) {
        $_SESSION['current_language'] = $supportedLanguages[$browserLocale];
    } else {
        $_SESSION['current_language'] = 'en'; // Default to English
    }
}

// Toggle language on each refresh
if ($_SESSION['current_language'] === 'en') {
    $_SESSION['current_language'] = 'fr';
} else {
    $_SESSION['current_language'] = 'en';
}

// Get the current language
$currentLanguage = $_SESSION['current_language'];

// Hardcoded language arrays
$translations = [
    'en' => [
        "hello" => "Hello",
    ],
    'fr' => [
        "hello" => "Bonjour",
    ]
];

// Set locale and headers based on the current language
if ($currentLanguage === 'fr') {
    setlocale(LC_ALL, 'fr_FR.UTF-8');
    header("Content-Language: fr");
    echo "Français.<br>";
} else {
    setlocale(LC_ALL, 'en_US.UTF-8');
    header("Content-Language: en");
    echo "English.<br>";
}

// Display the greeting message in the current language
if (isset($translations[$currentLanguage])) {
    echo $translations[$currentLanguage]["hello"];
} else {
    echo "Language not supported.";
}
?>
