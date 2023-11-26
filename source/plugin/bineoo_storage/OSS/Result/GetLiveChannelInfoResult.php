<?php

require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/Model/GetLiveChannelInfo.php';


class GetLiveChannelInfoResult extends Result
{
    /**
     * @return
     */
    protected function parseDataFromResponse()
    {
        $content = $this->rawResponse->body;
        $channelList = new GetLiveChannelInfo();
        $channelList->parseFromXml($content);
        return $channelList;
    }
}
