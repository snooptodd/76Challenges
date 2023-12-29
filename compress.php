<?php
$phar = new \Phar(__DIR__ . '/libraies.phar');    
//$phar->startBuffering(); // For performance reasons. Ordinarily, every time a file within a Phar archive is created or modified in any way, the entire Phar archive will be recreated with the changes.  
$phar->addFile('composer.json');
$phar->addFile('composer.lock');
$phar->buildFromDirectory(__DIR__ . '/vendor');
$phar->setDefaultStub('phar_bootstrap.php', 'phar_bootstrap.php');
$phar->stopBuffering();
// $phar1 = new \Phar(__DIR__ . '/76Challenges.phar');    
// //$phar->startBuffering(); // For performance reasons. Ordinarily, every time a file within a Phar archive is created or modified in any way, the entire Phar archive will be recreated with the changes.  
// $phar1->addFile('76Challenges.php');
// $phar1->addFile('libraies.phar');
// $phar1->addFile('challenges.txt');
// $phar1->addFile('bos.png');
// $phar1->addFile('MinervaLocation.txt');
// $phar1->addFile('MinervaEnventory.txt');
// $phar1->setDefaultStub('76Challenges.php', '76Challenges.php');
// $phar1->stopBuffering();