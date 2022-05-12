<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Wreload$~i', $args->text)) {
        yield from $this->onStart();
        yield $respond("`Plugins' list was reloaded.`");
    }
};
