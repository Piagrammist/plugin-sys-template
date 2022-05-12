<?php

return function () {
    yield $this->sendMsg('me', "`Called the 'Test Loop' at ".\date('m/d H:i:s').'`', 'Markdown');

    return 20_000;  // Repeat every 20 seconds
};
