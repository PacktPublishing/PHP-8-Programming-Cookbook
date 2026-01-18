<?php
// Define field attributes with validators
$fieldAttrs = [
    'first_name' => [
        'label' => 'First Name',
        'type' => 'text',
        'maxlength' => 20,
        'required' => true,
        'placeholder' => '',
        'validator' => function($value, $countries = null) {
            $value = trim(strip_tags($value));
            $errors = [];            
            if (empty($value)) {
                $errors[] = 'First name is required.';
            } elseif (strlen($value) > 20) {
                $errors[] = 'First name must not exceed 20 characters.';
            } elseif (!preg_match('/^[A-Za-z ,.]+$/', $value)) {
                $errors[] = 'First name can only contain letters, spaces, commas, and periods.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ],
    'last_name' => [
        'label' => 'Last Name',
        'type' => 'text',
        'maxlength' => 40,
        'required' => true,
        'placeholder' => '',
        'validator' => function($value, $countries = null) {
            $value = trim(strip_tags($value));
            $errors = [];            
            if (empty($value)) {
                $errors[] = 'Last name is required.';
            } elseif (strlen($value) > 40) {
                $errors[] = 'Last name must not exceed 40 characters.';
            } elseif (!preg_match('/^[A-Za-z \-,.]+$/', $value)) {
                $errors[] = 'Last name can only contain letters, spaces, dashes, commas, and periods.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ],
    'address1' => [
        'label' => 'Address Line 1',
        'type' => 'text',
        'required' => true,
        'placeholder' => 'House or building number and street name',
        'validator' => function($value, $countries = null) {
            $value = trim(strip_tags($value));
            $errors = [];
            if (empty($value)) {
                $errors[] = 'Address line 1 is required.';
            } elseif (strlen($value) > 64) {
                $errors[] = 'Address line 1 must not exceed 64 characters.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ],
    'address2' => [
        'label' => 'Address Line 2',
        'type' => 'text',
        'required' => false,
        'placeholder' => 'Apartment, suite, floor, etc.',
        'validator' => function($value, $countries = null) {
            $value = trim(strip_tags($value));
            $errors = [];
            if (strlen($value) > 64) {
                $errors[] = 'Address line 2 must not exceed 64 characters.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ],
    'city' => [
        'label' => 'City/Town',
        'type' => 'text',
        'required' => true,
        'placeholder' => '',
        'validator' => function($value, $countries = null) {
            $value = trim(strip_tags($value));
            $errors = [];            
            if (empty($value)) {
                $errors[] = 'City is required.';
            } elseif (strlen($value) > 64) {
                $errors[] = 'City must not exceed 64 characters.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ],
    'state_province' => [
        'label' => 'State/Province',
        'type' => 'text',
        'required' => true,
        'placeholder' => '',
        'validator' => function($value, $countries = null) {
            $value = trim(strip_tags($value));
            $errors = [];
            if (empty($value)) {
                $errors[] = 'State/Province is required.';
            } elseif (strlen($value) > 64) {
                $errors[] = 'State/Province must not exceed 64 characters.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ],
    'post_code' => [
        'label' => 'Postal Code',
        'type' => 'text',
        'required' => true,
        'placeholder' => '',
        'validator' => function($value, $countries = null) {
            $value = trim(strip_tags($value));
            $errors = [];
            if (empty($value)) {
                $errors[] = 'Postal code is required.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ],
    'country' => [
        'label' => 'Country',
        'type' => 'select',
        'required' => true,
        'validator' => function($value, $countries) {
            $value = trim(strip_tags($value));
            $errors = [];
            if (empty($value)) {
                $errors[] = 'Country is required.';
            } elseif (!array_key_exists($value, $countries)) {
                $errors[] = 'Invalid country code selected.';
            }
            return ['value' => $value, 'errors' => $errors];
        }
    ]
];

// Array of countries with ISO2 codes
$countries = [
    'US' => 'United States',
    'GB' => 'United Kingdom',
    'CA' => 'Canada',
    'AU' => 'Australia',
    'NZ' => 'New Zealand',
    'IE' => 'Ireland',
    'AF' => 'Afghanistan',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AG' => 'Antigua and Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia and Herzegovina',
    'BW' => 'Botswana',
    'BR' => 'Brazil',
    'BN' => 'Brunei',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CV' => 'Cape Verde',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo',
    'CR' => 'Costa Rica',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'CD' => 'Democratic Republic of the Congo',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GR' => 'Greece',
    'GD' => 'Grenada',
    'GT' => 'Guatemala',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HN' => 'Honduras',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran',
    'IQ' => 'Iraq',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Laos',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'MX' => 'Mexico',
    'FM' => 'Micronesia',
    'MD' => 'Moldova',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'KP' => 'North Korea',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'QA' => 'Qatar',
    'RO' => 'Romania',
    'RU' => 'Russia',
    'RW' => 'Rwanda',
    'KN' => 'Saint Kitts and Nevis',
    'LC' => 'Saint Lucia',
    'VC' => 'Saint Vincent and the Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome and Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'KR' => 'South Korea',
    'SS' => 'South Sudan',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syria',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TO' => 'Tonga',
    'TT' => 'Trinidad and Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VA' => 'Vatican City',
    'VE' => 'Venezuela',
    'VN' => 'Vietnam',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe'
];
// Sort countries alphabetically by name
asort($countries);

/**
 * Validates form data using the validators defined in $fieldAttrs
 * 
 * @param array $fieldAttrs Field attributes with validator callbacks
 * @param array $countries Array of valid country codes
 * @param array $formData Submitted form data (typically $_POST)
 * @return array Returns validation result with 'valid', 'errors', and 'data' keys
 */
function validateFormData($fieldAttrs, $countries, $formData) {
    $allErrors = [];
    $cleanData = [];
    
    foreach ($fieldAttrs as $fieldName => $fieldInfo) {
        // Get the submitted value or empty string if not set
        $value = isset($formData[$fieldName]) ? $formData[$fieldName] : '';
        
        // Run the validator callback
        if (isset($fieldInfo['validator']) && is_callable($fieldInfo['validator'])) {
            $result = $fieldInfo['validator']($value, $countries);
            
            // Store cleaned value
            $cleanData[$fieldName] = $result['value'];
            
            // Store any errors
            if (!empty($result['errors'])) {
                $allErrors[$fieldName] = $result['errors'];
            }
        }
    }
    
    return [
        'valid' => empty($allErrors),
        'errors' => $allErrors,
        'data' => $cleanData
    ];
}

/**
 * Generates HTML form for name and address
 * 
 * @param array $fieldAttrs Field attributes array
 * @param array $countries Countries array with ISO2 codes
 * @param string $formAction Form action URL
 * @param string $formMethod Form method (GET or POST)
 * @return string HTML form code
 */
function generateNameAddressForm($fieldAttrs, $countries, $formAction = '', $formMethod = 'POST') 
{
    
    // Start building HTML
    $html = '<form action="' . $formAction . '" method="' . $formMethod . '" accept-charset="UTF-8">';
    
    // Loop through form elements and generate HTML for each
    foreach ($fieldAttrs as $fieldName => $fieldInfo) {
        $fieldLabel = $fieldInfo['label'];
        $attrs = $fieldInfo;
        unset($attrs['label']);
        unset($attrs['validator']); // Don't include validator in HTML generation
        $isRequired = $attrs['required'];        
        $html .= '<div class="form-group">' . PHP_EOL;
        $html .= '<label for="' . $fieldName . '"';
        $html .= ($isRequired) ? ' class="required"' : '';
        $html .= '>' . $fieldLabel;
        $html .= (!$isRequired) ? ' <span class="optional">(Optional)</span>' : '';
        $html .= '</label>' . PHP_EOL;        
        // Generate input field based on type
        if ($attrs['type'] === 'select') {
            // Generate select dropdown for country
            $html .= '<select id="' . $fieldName . '" name="' . $fieldName . '"';
            $html .= ($isRequired) ? ' required': '';
            $html .= '>' . PHP_EOL;
            $html .= '<option value="">-- Select a country --</option>' . PHP_EOL;            
            foreach ($countries as $code => $name) {
                $html .= '<option value="' . $code . '">' . $name . '</option>' . PHP_EOL;
            }
            $html .= '</select>' . PHP_EOL;
        } else {
            // Generate text input
            $html .= '<input type="' . $attrs['type'] . '" ';
            $html .= 'id="' . $fieldName . '" ';
            $html .= 'name="' . $fieldName . '"';
            $html .= (isset($attrs['maxlength'])) ? ' maxlength="' . intval($attrs['maxlength']) . '"' : '';
            $html .= (!empty($attrs['placeholder'])) ? ' placeholder="' . $attrs['placeholder'] . '"' : '';
            $html .= ($isRequired) ? ' required' : '';
            $html .= '>' . PHP_EOL . PHP_EOL;
        }        
        $html .= '</div>'  . PHP_EOL . PHP_EOL;
    }    
    // Add submit button
    $html .= '<div class="form-group">'
           . '<button type="submit">Submit</button>'
           . '</div>'
           . '</form>';
    
    return $html;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $html = '';
    $validation = validateFormData($fieldAttrs, $countries, $_POST);    
    if ($validation['valid']) {
        $html .= '<div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;">';
        $html .= '<h3>Form submitted successfully!</h3>';
        $html .= '<h4>Submitted Data:</h4>';
        $html .= '<ul>';
        foreach ($validation['data'] as $field => $value) {
            $html .= '<li><strong>' . $field . ':</strong> ' . htmlspecialchars($value) . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    } else {
        $html .= '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px;">';
        $html .= '<h3>Please correct the following errors:</h3>';
        $html .= '<ul>';
        foreach ($validation['errors'] as $field => $errors) {
            foreach ($errors as $error) {
                $html .= '<li><strong>' . $field . ':</strong> ' . $error . '</li>';
            }
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
} else {
    $html = generateNameAddressForm($fieldAttrs, $countries, basename(__FILE__), 'POST');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Name and Address Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .optional {
            font-weight: normal;
            font-style: italic;
            color: #666;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>Name and Address Form</h1>
<?= $html ?>
</body>
</html>
