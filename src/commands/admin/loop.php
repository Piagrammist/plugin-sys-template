<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Wloop ((?:en|dis)able) (.+)$~i', $args->text, $match)) {
        $start = \strtolower($match[1]) === 'enable';
        $loop  = $this->getLoop(\trim($match[2]));
        if (!$loop) {
            return $respond("**Error**\n`Invalid name provided!`");
        }

        if ($start) {
            if (!$loop->isRunning()) {
                $loop->start();
                return $respond('`Loop started successfully.`');
            }
            return $respond('`Loop is already running!`');
        }
        if ($loop->isRunning()) {
            $loop->signal(true);
            return $respond('`Loop stopped successfully.`');
        }
        return $respond('`Loop is already stopped!`');
    }
};
