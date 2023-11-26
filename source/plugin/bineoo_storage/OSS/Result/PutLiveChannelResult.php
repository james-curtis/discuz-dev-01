<?php

require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/Model/LiveChannelInfo.php';

class PutLiveChannelResult extends Result
{
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channel = new LiveChannelInfo();
        $channel->parseFromXml($content);
        return $channel;
    }
}
