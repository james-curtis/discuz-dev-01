<?php


require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/Core/OssException.php';

/**
 * Class UploadPartResult
 * @package OSS\Result
 */
class UploadPartResult extends Result
{
    /**
     * 结果中part的ETag
     *
     * @return string
     * @throws OssException
     */
    protected function parseDataFromResponse()
    {
        $header = $this->rawResponse->header;
        if (isset($header["etag"])) {
            return $header["etag"];
        }
        throw new OssException("cannot get ETag");

    }
}