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
     * @var string
     *
     * @ORM\Column(name="lastDices", type="string", length=255, nullable=true)
     */
    private $lastDices;

    /**
     * @var int
     *
     * @ORM\Column(name="played", type="integer")
     */
    private $played;

    /**
     * @ORM\ManyToOne(targetEntity="RollRollBundle\Entity\Player", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="RollRollBundle\Entity\Game", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @var int
     *
     * @ORM\Column(name="playerOrder", type="integer")
     */
    private $playerOrder;


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
     * Set playerOrder
     *
     * @param integer $playerOrder
     *
     * @return Grid
     */
    public function setPlayerOrder($playerOrder)
    {
        $this->playerOrder = $playerOrder;

        return $this;
    }

    /**
     * Get playerOrder
     *
     * @return int
     */
    public function getPlayerOrder()
    {
        return $this->playerOrder;
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

    /**
     * Set game
     *
     * @param RollRoll\RollRollBundle\Entity\PGame $game
     * @return Grid
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return RollRoll\RollRollBundle\Entity\PGame
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set lastDices
     *
     * @param String lastDices
     * @return Grid
     */
    public function setLastDices($lastDices)
    {
        $this->lastDices = $lastDices;

        return $this;
    }

    /**
     * Get lastDices
     *
     * @return String
     */
    public function getLastDices()
    {
        return $this->lastDices;
    }
}

