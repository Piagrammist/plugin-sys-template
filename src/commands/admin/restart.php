<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Wrestart$~i', $args->text)) {
        if (\PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg') {
            return $respond("`This command only works on web server!`");
        }

        yield $respond("`Robot was restarted.`");
        $this->restart();
    }
};
