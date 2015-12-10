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
     * @var int
     *
     * @ORM\Column(name="missed", type="integer", options={"default": 0})
     */
    private $missed = 0;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function miss()
    {
        $this->missed++;
    }

    public function isComplete()
    {
        $l = 0;
        if($this->isLineFilled(0)) {
            $l++;
        }
        if($this->isLineFilled(1)) {
            $l++;
        }
        if($this->isLineFilled(2)) {
            $l++;
        }

        //Deux lignes sont remplies
        if($l >= 2) {
            return true;
        }

        if($this->missed >= 4) {
            return true;
        }

        return false;
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
            $this->scoreSheet = "0-0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0-0";
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
            $this->scoreSheet = "0-0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0-0";
            return $this->getCase($c,$i);
        }

        $ligne = explode("-", $sc[$c]);
        return $ligne[$i];
    }

    private function isLineFilled($line)
    {
        for($i = 0; $i < 9; $i++) {
            if($this->getCase($line,$i) == 0) {
                return false;
            }
        }
        return true;
    }

    private function getLineScore($line) {
        if($this->isLineFilled($line)) {
            $t = 0;
            for($i = 0; $i < 9; $i++) {
                if($this->getCase($line,$i) > $t) {
                    $t = $this->getCase($line,$i);
                }
            }
            return $t;
        } else {
            $t = 0;
            for($i = 0; $i < 9; $i++) {
                if($this->getCase($line,$i) != 0) {
                    $t++;
                }
            }
            return $t;
        }
    }

    public function getScore()
    {
        $score = $this->getLineScore(0)+$this->getLineScore(1)+$this->getLineScore(2)-5*$this->getMissed();

        if($this->getCase(0,0) != 0 && $this->getCase(1,1) != 0 && $this->getCase(2,2) != 0) {
            $score += $this->getCase(2,2);
        }

        if($this->getCase(0,1) != 0 && $this->getCase(1,2) != 0 && $this->getCase(2,3) != 0) {
            $score += $this->getCase(0,1);
        }

        if($this->getCase(0,4) != 0 && $this->getCase(1,5) != 0 && $this->getCase(2,6) != 0) {
            $score += $this->getCase(0,4);
        }

        if($this->getCase(0,5) != 0 && $this->getCase(1,6) != 0 && $this->getCase(2,7) != 0) {
            $score += $this->getCase(1,6);
        }

        if($this->getCase(0,6) != 0 && $this->getCase(1,7) != 0 && $this->getCase(2,8) != 0) {
            $score += $this->getCase(2,8);
        }

        return $score;
    }


    public function setCase($c,$i,$v)
    {
        if($i < 0 || $i > 9 || $c < 0 || $c > 2) {
            return;
        }
        
        $sc = explode("--", $this->scoreSheet);
        if(count($sc) != 3) {
            $this->scoreSheet = "0-0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0-0--0-0-0-0-0-0-0-0-0";
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

    /**
     * Set missed
     *
     * @param String missed
     * @return Grid
     */
    public function setMissed($missed)
    {
        $this->missed = $missed;

        return $this;
    }

    /**
     * Get missed
     *
     * @return String
     */
    public function getMissed()
    {
        return $this->missed;
    }
}

