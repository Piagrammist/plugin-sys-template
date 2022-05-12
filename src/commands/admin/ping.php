<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Wping$~i', $args->text)) {
        $start  = \microtime(true);
        $sentId = static::getSentId(yield $respond('`PONG`'));
        $ping   = \round((\microtime(true) - $start) * 1000, 3);
        yield $this->sleep(2);
        yield $this->editMsg($args->chatId, $sentId, "`Ping: $ping ms`", 'Markdown');
    }
};
