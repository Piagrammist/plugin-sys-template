<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;
use function Piagrammist\PluginSys\{alignMap, dashedToCamel};

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Wcommands$~i', $args->text)) {
        $chunks = [];
        foreach ($this->commands as $command) {
            list($group, $name) = \array_map(static fn($e) => dashedToCamel($e), \explode(':', (string) $command));
            if (!isset($chunks[$group])) {
                $chunks[$group] = [];
            }
            $chunks[$group][$name] = $command->isActive() ? 'active' : 'inactive';
        }
        $response = "**Commands List**";
        foreach ($chunks as $group => $commands) {
            $response .= "\n\n_{$group}_\n" . alignMap($commands);
        }
        return $respond($response);
    }
};
