<?php

use Piagrammist\PluginSys\GenericLoop;

return function (GenericLoop $loop) {
    yield $this->sendMsg('me', "`Called the 'Test Loop' at ".\date('m/d H:i:s').'`', 'Markdown');
    yield $this->sendMsg('me', "`Stopping the loop...`", 'Markdown');
    $loop->signal(true);  // Stop the loop

    return 20_000;  // Repeat every 20 seconds
};
