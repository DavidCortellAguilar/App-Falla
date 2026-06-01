<?php

require 'vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$keys = VAPID::createVapidKeys();

echo "Public Key:\n";
echo $keys['publicKey'];

echo "\n\nPrivate Key:\n";
echo $keys['privateKey'];
