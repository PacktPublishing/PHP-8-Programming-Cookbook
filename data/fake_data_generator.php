<?php
// How much data?
$how_much = 5000;
// Name of output files
$csv_file = 'fake.csv';
$sql_file = 'fake.sql';
// Fake Data Generator
$last_name = file('last_names.txt');
$female_name = file('female_name.txt');
$male_name = file('male_name.txt');
$street1 = ['Amber','Blue','Bright','Broad','Burning','Cinder','Clear','Colonial','Cotton','Cozy','Crystal','Dewy','Dusty','Easy','Emerald','Fallen','Foggy','Gentle','Golden','Grand','Green','Harvest','Hazy','Heather','Hidden','High','Honey','Indian','Iron','Jagged','Lazy','Little','Lost','Merry','Middle','Misty','Noble','Old','Pleasant','Quaking','Quiet','Red','Rocky','Round','Rustic','Shady','Silent','Silver','Sleepy','Stony','Sunny','Tawny','Thunder','Umber','Velvet','Wishing'];
$street2 = ['Anchor','Apple','Autumn','Barn','Beacon','Bear','Berry','Blossom','Bluff','Branch','Brook','Butterfly','Cider','Cloud','Creek','Dale','Deer','Elk','Embers','Fawn','Forest','Fox','Gate','Goose','Grove','Hickory','Hills','Horse','Island','Lagoon','Lake','Leaf','Log','Mountain','Nectar','Oak','Panda','Pine','Pioneer','Pond','Pony','Prairie','Quail','Rabbit','Rise','River','Robin','Shadow','Sky','Spring','Timber','Treasure','View','Wagon ','Willow','Zephyr'];
$street3 = ['Acres','Arbor','Avenue','Bank','Bend','Canyon','Chase','Circle','Corner','Court','Cove','Crest','Dale','Dell','Edge','Estates','Falls','Farms','Gardens','Gate','Glade','Glen','Grove','Highlands','Hollow','Isle','Jetty','Knoll','Landing','Lane','Ledge','Manor','Meadow','Mews','Nook','Orchard','Park','Path','Pike','Place','Point','Promenade','Ridge','Round','Run','Stead','Swale','Terrace','Trace','Trail','Vale','Valley','View','Vista','Way','Woods','Boulevard','Street','Lane','Drive'];
$town = file('town_state.txt');
try {
    // Generate fake data:
    // 0   1           2          3         4    5      6    7
    // id, first_name, last_name, address, city, state, zip, phone
    $fields = ['id', 'first_name', 'last_name', 'address', 'city', 'state', 'zip', 'phone'];
    $pdo = new PDO($db_dsn, $db_usr, $db_pwd);
    $sql = '';
    $stmt = $pdo->prepare($sql);
    if ($fh = fopen($csv_file,"w")) {
        for ($x = 1; $x < $how_much; $x++) {
            $line[0] = $x;
            if ($id % 3) {
                $line[1] = $female_name[array_rand($female_name)];
            } else {
                $line[1] = $male_name[array_rand($male_name)];
            }
            $line[2] = $last_name[array_rand($last_name)];
            $line[3] = rand(1,999) . " " . $street1[array_rand($street1)] . " " . $street2[array_rand($street2)] . " " . $street3[array_rand($street3)];
            list($a,$b) = explode(",",$town[array_rand($town)]);
            $line[4] = $a;
            $line[5] = trim($b);
            $line[6] = rand(10000,99999);
            $line[7] = sprintf("%3d-%3d-%04d", rand(101,888), rand(101,999), rand(1,9999));
            fputcsv($fh, $line);
            echo "\n" . implode(", ",$line);
        }
        fclose($fh);
    } else {
        echo "\nUnable to open $csv_file\n";
    }
} catch (Throwable $t) {
    echo $t;
}
