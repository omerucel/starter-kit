<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="session")
 * @ORM\Entity
 */
class Session
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="session_id", type="string", length=255, nullable=false)
     */
    private $session_id;

    /**
     * @var string
     * @ORM\Column(name="session_value", type="text", nullable=false)
     */
    private $session_value;

    /**
     * @var int
     * @ORM\Column(name="session_time", type="integer", length=11, nullable=false)
     */
    private $session_time;

    /**
     * @param string $session_id
     */
    public function setSessionId($session_id)
    {
        $this->session_id = $session_id;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * @param int $session_time
     */
    public function setSessionTime($session_time)
    {
        $this->session_time = $session_time;
    }

    /**
     * @return int
     */
    public function getSessionTime()
    {
        return $this->session_time;
    }

    /**
     * @param string $session_value
     */
    public function setSessionValue($session_value)
    {
        $this->session_value = $session_value;
    }

    /**
     * @return string
     */
    public function getSessionValue()
    {
        return $this->session_value;
    }
}
