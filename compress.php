<?php
$pharFile = 'libraies.phar';
// clean up
if (file_exists($pharFile)) 
{
    unlink($pharFile);
}
$phar = new Phar($pharFile);
$phar->startBuffering(); 
//$phar->addFile('phar_bootstrap.php');
$phar->addFile('composer.json');
$phar->addFile('composer.lock');
$phar->buildFromDirectory(__DIR__ . '/vendor'); //path inside the phar does not include /vendor
$defaultStub = $phar->createDefaultStub('autoload.php'); 
$phar->setStub($defaultStub);
$phar->stopBuffering();

// $pharFile = 'app.phar';
// // clean up
// if (file_exists($pharFile)) 
// {
//     unlink($pharFile);
// }
// $phar = new Phar($pharFile);  
// $phar->startBuffering(); 
// $phar->addFile('76Challenges.php');
// $phar->addFile('libraies.phar');
// $phar->addFile('challenges.txt');
// $phar->addFile('bos.png');
// $phar->addFile('MinervaLocation.txt');
// $phar->addFile('MinervaEnventory.txt');
// $defaultStub = $phar->createDefaultStub('76Challenges.php');
// $phar->setStub($defaultStub);
// $phar->stopBuffering();