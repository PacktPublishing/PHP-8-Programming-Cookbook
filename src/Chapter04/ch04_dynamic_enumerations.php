<?php
include __DIR__ . '/../../vendor/autoload.php';
use Cookbook\Appointment\{Visitor,Gender,Calendar,DynamicEnum};
// load dynamic Months enum
eval(DynamicEnum::getMonthsEnum());
// generate test data
$max     = 12;
$names   = ['Kirk','Spock','Scotty','Uhuru','Sulu','Checkov','Chapel','McCoy'];
$mins    = [0, 15, 30, 45];
$year    = date('Y');
$month   = \Cookbook\Appointment\Months::getRandom();
$calendar = new Calendar();
for ($x = 0; $x < $max; $x++) {
    $id = 100 + $x;
    $name = $names[array_rand($names)];    
    $gender = (in_array($name, ['Uhuru','Chapel'])) ? Gender::F : Gender::M;
    $lastDay = $calendar->getLastDay($month);
    $day = rand(1, $lastDay);
    $time = sprintf('%02d:%02d', rand(10,13), $mins[array_rand($mins)]);
    $calendar->add(new Visitor($id, $name, $gender, $month, $day, $time));
}
echo $calendar->viewAppts($month);

