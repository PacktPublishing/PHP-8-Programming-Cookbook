<?php
function generateNameAddressForm($formAction = '', $formMethod = 'POST') {
    // Define form elements with their labels
    $formElements = [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'address1' => 'Address Line 1',
        'address2' => 'Address Line 2',
        'city' => 'City/Town',
        'state_province' => 'State/Province',
        'post_code' => 'Postal Code',
        'country' => 'Country'
    ];
    
    // Define field attributes
    $fieldAttributes = [
        'first_name' => ['type' => 'text', 'maxlength' => 20, 'required' => true, 'placeholder' => ''],
        'last_name' => ['type' => 'text', 'maxlength' => 40, 'required' => true, 'placeholder' => ''],
        'address1' => ['type' => 'text', 'required' => true, 'placeholder' => 'House or building number and street name'],
        'address2' => ['type' => 'text', 'required' => false, 'placeholder' => 'Apartment, suite, floor, etc.'],
        'city' => ['type' => 'text', 'required' => true, 'placeholder' => ''],
        'state_province' => ['type' => 'text', 'required' => true, 'placeholder' => ''],
        'post_code' => ['type' => 'text', 'required' => true, 'placeholder' => ''],
        'country' => ['type' => 'select', 'required' => true]
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
    
    // Start building HTML
    $html = '<!DOCTYPE html>
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
    <form action="' . htmlspecialchars($formAction) . '" method="' . htmlspecialchars($formMethod) . '" accept-charset="UTF-8">
';
    
    // Loop through form elements and generate HTML for each
    foreach ($formElements as $fieldName => $fieldLabel) {
        $attrs = $fieldAttributes[$fieldName];
        $isRequired = $attrs['required'];
        
        $html .= '        <div class="form-group">' . "\n";
        $html .= '            <label for="' . htmlspecialchars($fieldName) . '"';
        $html .= $isRequired ? ' class="required"' : '';
        $html .= '>' . htmlspecialchars($fieldLabel);
        
        if (!$isRequired) {
            $html .= ' <span class="optional">(Optional)</span>';
        }
        
        $html .= '</label>' . "\n";
        
        // Generate input field based on type
        if ($attrs['type'] === 'select') {
            // Generate select dropdown for country
            $html .= '            <select id="' . htmlspecialchars($fieldName) . '" name="' . htmlspecialchars($fieldName) . '"';
            if ($isRequired) {
                $html .= ' required';
            }
            $html .= '>' . "\n";
            $html .= '                <option value="">-- Select a country --</option>' . "\n";
            
            foreach ($countries as $code => $name) {
                $html .= '                <option value="' . htmlspecialchars($code) . '">' . htmlspecialchars($name) . '</option>' . "\n";
            }
            
            $html .= '            </select>' . "\n";
        } else {
            // Generate text input
            $html .= '            <input type="' . htmlspecialchars($attrs['type']) . '" ';
            $html .= 'id="' . htmlspecialchars($fieldName) . '" ';
            $html .= 'name="' . htmlspecialchars($fieldName) . '"';
            
            if (isset($attrs['maxlength'])) {
                $html .= ' maxlength="' . $attrs['maxlength'] . '"';
            }
            
            if (!empty($attrs['placeholder'])) {
                $html .= ' placeholder="' . htmlspecialchars($attrs['placeholder']) . '"';
            }
            
            if ($isRequired) {
                $html .= ' required';
            }
            
            $html .= '>' . "\n";
        }
        
        $html .= '        </div>' . "\n\n";
    }
    
    // Add submit button
    $html .= '        <div class="form-group">
            <button type="submit">Submit</button>
        </div>
        
    </form>
</body>
</html>';
    
    return $html;
}

// Example usage:
echo generateNameAddressForm();
?>
