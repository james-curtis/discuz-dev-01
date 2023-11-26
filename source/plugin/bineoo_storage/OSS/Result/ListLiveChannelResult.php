<?php


require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/Model/LiveChannelListInfo.php';

class ListLiveChannelResult extends Result
{
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new LiveChannelListInfo();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
