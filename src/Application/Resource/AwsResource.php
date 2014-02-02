<?php

namespace Application\Resource;

use Aws\S3\Enum\CannedAcl;
use Aws\S3\S3Client;

trait AwsResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return S3Client
     */
    public function getS3Client()
    {
        if (!ResourceMemory::hasKey('aws_s3_client')) {
            $s3Client = S3Client::factory($this->getConfigs()['aws']);
            $s3Client->getConfig()->set('curl.options', array('body_as_string' => true));
            ResourceMemory::set('aws_s3_client', $s3Client);
        }

        return ResourceMemory::get('aws_s3_client');
    }

    /**
     * @return string
     */
    public function getS3Bucket()
    {
        return $this->getConfigs()['aws']['bucket'];
    }

    /**
     * @param $key
     * @param $filePath
     */
    protected function uploadImageToS3($key, $filePath)
    {
        $this->getS3Client()->putObject(
            array(
                'Bucket' => $this->getS3Bucket(),
                'Key' => $key,
                'SourceFile' => $filePath,
                'ACL' => CannedAcl::PUBLIC_READ,
                'CacheControl' => 'max-age=94608000',
                'Expires' => gmdate('D, d M Y H:i:s T', strtotime('+3 years')),
            )
        );
    }
}
