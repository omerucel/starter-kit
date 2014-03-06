<?php

namespace MiniFrame;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConfig()
    {
        $data = array(
            'facebook' => array(
                'app_id' => 123,
                'app_secret' => 'asdf'
            )
        );

        $config = new Config($data);
        $this->assertEquals(123, $config->get('facebook.app_id'));
        $this->assertEquals('asdf', $config->get('facebook.app_secret'));

        $config->set('facebook.app_secret', 'selam');
        $this->assertEquals('selam', $config->get('facebook.app_secret'));
    }
}
