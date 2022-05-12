<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if ($args->text === '{}') {
        $flags = \JSON_PRETTY_PRINT     |
                 \JSON_UNESCAPED_SLASHES|
                 \JSON_UNESCAPED_UNICODE;
        if ($args->replyTo) {
            $toDump = yield $this->getMsg($args->chatId, static::stringTypeToEnum($args->type), [$args->replyTo])['messages'][0];
        } else {
            $toDump = $update['message'];
        }
        return $respond('<code>'.\json_encode($toDump, $flags).'</code>', 'HTML');
    }
};
