<?php

namespace App\tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Classe de test pour Formation.php
 *
 * @author Maxat
 */
class FormationTest extends TestCase{
    
    public function testGetPublishedAtString(){
        $formation = new Formation();
        $publishedAt = new DateTime("2020-01-01");
        $formation->setPublishedAt($publishedAt);
        $this->assertEquals("01/01/2020", $formation->getPublishedAtString());
    }
    
}
