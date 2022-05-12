<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\W(?:command|cmd) ((?:en|dis)able) (.+:.+)$~i', $args->text, $match)) {
        $enable  = \strtolower($match[1]) === 'enable';
        $command = $this->getCommand(\trim($match[2]));
        if (!$command) {
            return $respond("**Error**\n`Invalid group/name provided!`");
        }

        if ($enable) {
            if (!$command->isActive()) {
                $command->activate();
                return $respond('`Command activated successfully.`');
            }
            return $respond('`Command was already active!`');
        }
        if ($command->isActive()) {
            $command->deactivate();
            return $respond('`Command deactivated successfully.`');
        }
        return $respond('`Command was already inactive!`');
    }
};
