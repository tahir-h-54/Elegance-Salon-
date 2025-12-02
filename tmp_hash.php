<?php
$passwords = ["Admin@2025!","FrontDesk@9","ShearMaster#7","ColorMagic#8","SpaZen#5","Inventory#4","ContentFlow#6"]; foreach ($passwords as $p) { echo $p . " => " . password_hash($p, PASSWORD_DEFAULT) . PHP_EOL; }

