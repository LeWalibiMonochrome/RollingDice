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

    public function getCases($c)
    {
        $sc = explode("--", $this->scoreSheet);
        if(count($sc) != 3) {
            $this->scoreSheet = "0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0";
            return $this->getCases($c);
        }

        return implode(",", explode("-", $sc[$c]));
    }

    public function getCase($c,$i)
    {
        if($i < 0 || $i > 8 || $c < 0 || $c > 2) {
            return 0;
        }

        $sc = explode("--", $this->scoreSheet);
        if(count($sc) != 3) {
            $this->scoreSheet = "0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0";
            return $this->getCase($c,$i);
        }

        $ligne = explode("-", $sc[$c]);
        return $ligne[$i];
    }


    public function setCase($c,$i,$v)
    {
        if($i < 0 || $i > 8 || $c < 0 || $c > 2) {
            return;
        }
        
        $sc = explode("--", $this->scoreSheet);
        if(count($sc) != 3) {
            $this->scoreSheet = "0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0";
            return $this->setCase($c,$i,$v);
        }

        $lignes = array(
            explode("-",$sc[0]),
            explode("-",$sc[1]),
            explode("-",$sc[2])
        );

        $lignes[$c][$i] = $v;

        $this->scoreSheet = implode("-", $lignes[0]).'--'.implode("-", $lignes[1]).'--'.implode("-", $lignes[2]);
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

