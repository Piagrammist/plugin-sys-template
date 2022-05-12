<?php

use danog\MadelineProto\Logger;
use Piagrammist\PluginSys\Utils\ParamsWrapper;
use danog\MadelineProto\Settings\Database\Memory as MemoryDatabase;

use function Amp\File\{getSize, isFile};
use function Piagrammist\PluginSys\{
    readableBytes,
    getCpuCores,
    alignMap,
    path,
};

return function (array $update, ParamsWrapper $args, callable $respond) {
    if (\preg_match('~^\Wstats$~i', $args->text)) {
        if (!($this->self['bot'] ?? true)) {
            $chats = ['bot' => 0, 'user' => 0, 'chat' => 0, 'channel' => 0, 'supergroup' => 0];
            foreach (yield $this->getDialogs() as $dialog) {
                // Skip 'Saved Messages'
                if ($dialog['_'] === 'peerUser' && $dialog['user_id'] === $this->self['id']) {
                    continue;
                }

                try {
                    $chats[yield $this->getInfo($dialog)['type']]++;
                } catch (\Throwable $e) {
                    $this->logger($e, Logger::LEVEL_FATAL);
                }
            }
            $contacts = yield $this->contacts->getContacts();
            $mutual   = 0;
            foreach ($contacts['contacts'] as $contact) {
                if ($contact['mutual']) {
                    $mutual++;
                }
            }
            $chatStats =
                "**Chats**\n"
                .alignMap([
                    'Bot'        => $chats['bot'],
                    'User'       => $chats['user'],
                    'Group'      => $chats['chat'],
                    'Channel'    => $chats['channel'],
                    'Supergroup' => $chats['supergroup'],
                ])
                ."\n";
            $contactStats =
                "**Contacts**\n"
                .alignMap([
                    'Contact'        => $contacts['saved_count'],
                    'Mutual Contact' => $mutual,
                ])
                ."\n";
        }
        $decimals = 2;
        if ($this->API->settings->getDb() instanceof MemoryDatabase && static::$sessionDir) {
            $sessionPath = path(static::$sessionDir, 'session.safe.php');
            $sessionSize = (yield isFile($sessionPath))
                ? readableBytes(yield getSize($sessionPath), $decimals) : 'NOT_FOUND';
        } else {
            $sessionSize = 'USING_DATABASE';
        }
        if ($this->API->settings->getLogger()->getType() === Logger::FILE_LOGGER) {
            $logPath = $this->API->settings->getLogger()->getExtra();
            $logSize = (yield isFile($logPath))
                ? readableBytes(yield getSize($logPath), $decimals) : 'NOT_FOUND';
        } else {
            $logSize = 'NOT_FOUND';
        }
        $serverStats = 
            "**Server**\n"
            .alignMap([
                'CPU cores'             => getCpuCores(),
                'Bot   mem. usage'      => readableBytes(\memory_get_usage()         , $decimals),
                'Total mem. usage'      => readableBytes(\memory_get_usage(true)     , $decimals),
                'Bot   peak mem. usage' => readableBytes(\memory_get_peak_usage()    , $decimals),
                'Total peak mem. usage' => readableBytes(\memory_get_peak_usage(true), $decimals),
                'Logs    size'          => $logSize,
                'Session size'          => $sessionSize,
                'PHP version'           => \PHP_VERSION,
            ]);
        return $respond("**Robot Statistics**\n\n" . ($chatStats ?? '') . ($contactStats ?? '') . $serverStats);
    }
};
