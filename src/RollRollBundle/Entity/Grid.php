<?php

namespace RollRollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grid
 *
 * @ORM\Table(name="grid")
 * @ORM\Entity(repositoryClass="RollRollBundle\Repository\GridRepository")
 */
class Grid
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
     * @ORM\Column(name="scoreSheet", type="string", length=255, nullable=true)
     */
    private $scoreSheet;

    /**
     * @var int
     *
     * @ORM\Column(name="played", type="integer")
     */
    private $played;

    /**
     * @ORM\ManyToOne(targetEntity="RollRoll\RollRollBundle\Entity\Player", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;


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
     * Set scoreSheet
     *
     * @param string $scoreSheet
     *
     * @return Grid
     */
    public function setScoreSheet($scoreSheet)
    {
        $this->scoreSheet = $scoreSheet;

        return $this;
    }

    /**
     * Get scoreSheet
     *
     * @return string
     */
    public function getScoreSheet()
    {
        return $this->scoreSheet;
    }

    /**
     * Set played
     *
     * @param integer $played
     *
     * @return Grid
     */
    public function setPlayed($played)
    {
        $this->played = $played;

        return $this;
    }

    /**
     * Get played
     *
     * @return int
     */
    public function getPlayed()
    {
        return $this->played;
    }

    /**
     * Set owner
     *
     * @param RollRoll\RollRollBundle\Entity\Player $owner
     * @return Grid
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return RollRoll\RollRollBundle\Entity\Player
     */
    public function getOwner()
    {
        return $this->owner;
    }
}

