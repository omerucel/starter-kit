<?php

namespace MiniFrame\Extra\Service;

use Aws\S3\Enum\CannedAcl;
use Aws\S3\S3Client;
use MiniFrame\BaseService;

class AwsService extends BaseService
{
    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * @return S3Client
     */
    public function getS3Client()
    {
        if ($this->s3Client == null) {
            $this->s3Client = S3Client::factory($this->getConfigs()->getArray('aws.s3'));
        }

        return $this->s3Client;
    }

    /**
     * @return string
     */
    public function getS3Bucket()
    {
        return $this->getConfigs()->get('aws.s3.bucket');
    }

    /**
     * @param $key
     * @param $filePath
     */
    public function uploadToS3($key, $filePath)
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
