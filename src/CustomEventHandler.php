<?php

use Piagrammist\PluginSys\Utils\ParamsWrapper;
use Piagrammist\PluginSys\EventHandler as BaseEventHandler;

class CustomEventHandler extends BaseEventHandler
{
    /* Override 'groupsToExecute' method for-
     *   your new rules on execution of the commands.
     *
     * public function groupsToExecute(array $update, ParamsWrapper $params): array
     * {
     *     $groups = ['public'];
     *     if ($params->isOut) {
     *         $groups []= 'admin';
     *     } elseif ($params->fromId === static::$owner) {
     *         $groups []= 'owner';
     *         $groups []= 'admin';
     *     }
     *     return $groups;
     * }
     */

    public function onUpdateEditChannelMessage(array $update)
    {
        return $this->onUpdateEditMessage($update);
    }
    public function onUpdateEditMessage(array $update)
    {
        return $this->onUpdateNewMessage($update);
    }
}
