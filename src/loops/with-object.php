<?php

use Piagrammist\PluginSys\GenericLoop;

return function (GenericLoop $loop) {
    yield $this->sendMsg('me', "`Called the 'Test Loop (w/ object)' at ".\date('m/d H:i:s').'`', 'Markdown');

    return 20_000;  // Repeat every 20 seconds
};
