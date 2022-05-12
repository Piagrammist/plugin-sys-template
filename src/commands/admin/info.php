<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;
use function Piagrammist\PluginSys\alignMap;

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Winfo ?(@\w{5,32}|-?\d+)?$~i', $args->text, $match)) {
        $chat   = $match[1] ?? null;
        $chunks = [];
        if (!$chat) {
            if ($args->replyTo) {
                $msg = yield $this->getMsg($args->chatId, static::stringTypeToEnum($args->type), [$args->replyTo])['messages'][0];
                $chunks['ReplyTo Id'] = $msg['id'];
                $chunks['From Id']    = $msg['from_id']['user_id'];
            } else {
                $chunks['Message Id'] = $args->msgId;
                $chunks['User Id']    = $args->fromId;
            }
            $chunks['Chat Id']   = $args->chatId;
            $chunks['Chat Type'] = \ucfirst($args->type);
        } else {
            try {
                $info = yield $this->getInfo($chat);
            } catch (\Throwable) {
                return $respond("**Error**\n`Chat not found!`");
            }
            $chunks['Chat Id']   = $info['bot_api_id'];
            $chunks['Chat Type'] = \ucfirst($info['type']);
            /*switch ($info['type']) {
                case 'channel':
                case 'supergroup':
                    break;
                case 'chat':
                    break;
                case 'bot':
                case 'user':
                    break;
            }*/
        }
        return $respond("**Info**\n\n" . alignMap($chunks));
    }
};
