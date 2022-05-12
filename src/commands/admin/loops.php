<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;
use function Piagrammist\PluginSys\{alignMap, dashedToCamel};

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Wloops$~i', $args->text)) {
        if (\count($this->loops) === 0) {
            return $respond('`No loop available!`');
        }

        $chunks = [];
        foreach ($this->loops as $loop) {
            $chunks[dashedToCamel((string) $loop)] = $loop->isRunning() ? 'running' : 'stopped';
        }
        return $respond("**Loops List**\n\n" . alignMap($chunks));
    }
};
