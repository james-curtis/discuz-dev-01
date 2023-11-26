<?php

require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/Model/GetLiveChannelHistory.php';

class GetLiveChannelHistoryResult extends Result
{
    /**
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelHistory();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
