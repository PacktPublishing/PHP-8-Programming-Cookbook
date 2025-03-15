<?php
$vehicleData = [
    "total_results" => 3,
    "page" => 1,
    "page_size" => 10,
    "results" => [
        [
            "Make" => "Mercedes-Benz",
            "Model" => "CLA",
            "Year" => 2025,
            "Price" => 38000,
            "Mileage" => 15000,
            "Condition" => "New",
            "Description" => "Compact luxury car with modern design",
            "Additional_Info" => [
                "Color" => "Red",
                "Tyres" => "Michelin",
            ],
        ],
        [
            "Make" => "Mercedes-Benz",
            "Model" => "S Class",
            "Year" => 2010,
            "Price" => 45000,
            "Mileage" => 5000,
            "Condition" => "Used",
            "Description" => "Full-sized sedan with 5 seats",
            "Additional_Info" => [
                "Color" => "White",
                "Tyres" => "Pirelli",
            ],
        ],
        [
            "Make" => "Honda",
            "Model" => "Civic",
            "Year" => 2025,
            "Price" => 45000,
            "Mileage" => 0,
            "Condition" => "New",
            "Description" => "Sedan with 5 seats",
            "Additional_Info" => [
                "Color" => "Black",
                "Tyres" => "Pirelli",
            ],
        ],
    ]
];

$totalPrice = array_sum(array_column($vehicleData["results"], "Price"));

function renderDetails(array $data, string $prefix, int $index)
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            echo '<div class="additional-header" onclick="toggleDetails(\'' . $prefix . $index . '\')">' . htmlspecialchars(
                    $key
                ) . '</div>';
            echo '<div class="additional-details" id="' . $prefix . $index . '" style="display:none">';
            renderDetails($value, $prefix . $index . '_', $index);
            echo '</div>';
        } else {
            echo '<p><strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($value) . '</p>';
        }
    }
}

function renderCarDetails(array $car, int $index)
{
    echo '<div class="car-details" id="car' . $index . '" style="display:none">';
    foreach ($car as $key => $value) {
        if (is_array($value)) {
            echo '<div class="additional-header" onclick="toggleDetails(\'additional' . $index . '\')">Additional Info</div>';
            echo '<div class="additional-details" id="additional' . $index . '" style="display:none">';
            renderDetails($value, 'additional' . $index . '_', $index);
            echo '</div>';
        } else {
            echo '<p><strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($value) . '</p>';
        }
    }
    echo '</div>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collapsible Car Listings</title>
    <style>body{font-family:"Arial"}.container{width:300px;float: left;}.car-header{background-color:black;color:white;padding:5px;cursor:pointer;margin:0 0 3px 0}.car-details,.additional-details{display:none;padding:5px;border:1px solid #000}.additional-header{background-color:red;color:white;padding:5px;cursor:pointer}p{margin:3px 0}</style>
    <script>
        (function () {
            function createToggleFunction(elementId) {
                return function () {
                    var detailsElement = document.getElementById(elementId);
                    if (detailsElement.style.display === "none" || detailsElement.style.display === "") {
                        detailsElement.style.display = "block";
                    } else {
                        detailsElement.style.display = "none";
                    }
                };
            }

            window.toggleDetails = function (elementId) {
                var toggle = createToggleFunction(elementId);
                toggle();
            };
        })();
    </script>
</head>
<body>
<div class="container">
    <h2>Car Listings</h2>
    <h3>Total Price of All Vehicles: $<?= number_format($totalPrice) ?></h3>
    <?php
    foreach ($vehicleData["results"] as $index => $car) : ?>
        <div class="car-header" onclick="toggleDetails('car<?= $index ?>')">
            <?= htmlspecialchars($car["Make"] . " " . $car["Model"] . " (" . $car["Year"] . ")") ?>
        </div>
        <?php
        renderCarDetails($car, $index); ?>
    <?php
    endforeach; ?>
</div>
</body>
</html>