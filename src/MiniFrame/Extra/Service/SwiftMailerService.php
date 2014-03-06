<?php

namespace MiniFrame\Extra\Service;

use MiniFrame\BaseService;

class SwiftMailerService extends BaseService
{
    /**
     * @var \Swift_Mailer
     */
    protected $swiftMailer;

    /**
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        if ($this->swiftMailer == null) {
            $transport = \Swift_SmtpTransport::newInstance();
            $transport->setUsername($this->getConfigs()->get('swift_mailer.username'));
            $transport->setPassword($this->getConfigs()->get('swift_mailer.password'));
            $transport->setHost($this->getConfigs()->get('swift_mailer.host'));
            $transport->setPort($this->getConfigs()->get('swift_mailer.port'));

            $this->swiftMailer = \Swift_Mailer::newInstance($transport);
        }

        return $this->swiftMailer;
    }
}
