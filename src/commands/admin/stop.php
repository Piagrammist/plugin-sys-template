<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\W(?:stop|die)$~i', $args->text, $match)) {
        yield $respond("`Robot was stopped.`");
        $this->stop();
    }
};
