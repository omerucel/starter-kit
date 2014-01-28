<?php

namespace Application\Resource;

trait SwiftMailerResource
{
    /**
     * @return array
     */
    abstract public function getConfigs();

    /**
     * @return \Swift_Mailer
     */
    public function getSwiftMailer()
    {
        if (!ResourceMemory::hasKey('swiftmailer')) {
            $mailerConfigs = $this->getConfigs()['swiftmailer'];

            $transport = \Swift_SmtpTransport::newInstance();
            $transport->setUsername($mailerConfigs['username']);
            $transport->setPassword($mailerConfigs['password']);
            $transport->setHost($mailerConfigs['host']);
            $transport->setPort($mailerConfigs['port']);

            $mailer = \Swift_Mailer::newInstance($transport);
            ResourceMemory::set('mailer', $mailer);
        }

        return ResourceMemory::get('mailer');
    }
}
