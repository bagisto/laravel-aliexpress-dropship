<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Core\Repositories\ChannelRepository as BaseChannelRepository;

/**
 * Channel Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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