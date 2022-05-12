<?php

use Amp\Promise;
use Piagrammist\PluginSys\Utils\ParamsWrapper;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Weval(.+)$~is', $args->text, $match)) {
        $sentId = static::getSentId(yield $respond("`Executing...`"));
        $code   = \trim($match[1]);
        try {
            \ob_start();
            $res = eval(<<<CODE
                return (function () use (\$update, \$args) {
                    $code
                })();
            CODE);
            if ($res instanceof Promise) {
                yield $res;
            } elseif ($res instanceof \Generator) {
                yield from $res;
            }
            $result = \ob_get_clean();
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }
        $result = !empty($result) ? "\n\n<b>Result:</b>\n<code>$result</code>" : null;
        $error  = !empty($error)  ? "\n\n<b>Error:</b>\n<code>$error</code>"   : null;
        if (!$result && !$error) {
            $response = '<code>Done!</code>';
        } else {
            $response = "<b>Code:</b>\n<code>{$code}</code>{$result}{$error}";
        }
        yield $this->editMsg($args->chatId, $sentId, $response, 'HTML');
    }
};
