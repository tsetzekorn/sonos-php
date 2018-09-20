<?php
include "Sonos.php";

$sns = new Sns();
$players[] = "RINCON_XXXXXXXXXXXXXXXXX"; // Room1
$players[] = "RINCON_YYYYYYYYYYYYYYYYY"; // Room2
$group = $sns->households[0]->createGroup($players);
$group->loadStreamUrl("https://wdr-1live-live.icecastssl.wdr.de/wdr/1live/live/mp3/128/stream.mp3");
$group->setVolume(20);
$group->play();

echo "<pre>\n";
print_r($sns->households);
echo "</pre>";
?>