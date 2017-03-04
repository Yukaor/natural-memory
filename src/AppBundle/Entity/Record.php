<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Record
 *
 * @ORM\Table(name="record")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecordRepository")
 */
class Record
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="h", type="integer")
     */
    private $h;

    /**
     * @var int
     *
     * @ORM\Column(name="v", type="integer")
     */
    private $v;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer")
     */
    private $time;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Record
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set h
     *
     * @param integer $h
     *
     * @return Record
     */
    public function setH($h)
    {
        $this->h = $h;

        return $this;
    }

    /**
     * Get h
     *
     * @return int
     */
    public function getH()
    {
        return $this->h;
    }

    /**
     * Set v
     *
     * @param integer $v
     *
     * @return Record
     */
    public function setV($v)
    {
        $this->v = $v;

        return $this;
    }

    /**
     * Get v
     *
     * @return int
     */
    public function getV()
    {
        return $this->v;
    }

    /**
     * Set time
     *
     * @param integer $time
     *
     * @return Record
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }
}

