<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Core\Repositories\ChannelRepository as BaseChannelRepository;

class ChannelRepository extends BaseChannelRepository
{
    /**
     * Return all channels
     *
     * @return mixed
     */
    public function getChannels()
    {
        $channels = [];

        foreach ($this->all() as $channel) {
            $channels[$channel->code] = $channel->name;
        }

        return $channels;
    }
}