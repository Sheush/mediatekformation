<?php

namespace App\tests\controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of FormationControllerTest
 *
 * @author Maxat
 */
class FormationControllerTest extends WebTestCase{
    
    private const nombreFormations = 236;
    
    public function testSortOnTitleAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertCount(self::nombreFormations,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }
    
    public function testSortOnTitleDesc(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/DESC');
        $this->assertSelectorTextContains('h5',
                'UML : Diagramme de paquetages');
    }
    
    public function testSortOnPlaylistAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertCount(self::nombreFormations,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                'Bases de la programmation n°74 - POO : collections');
    }
    
     public function testSortOnPlaylistDesc(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('h5',
                'C# : ListBox en couleur');
    }
    
    public function testSortOnDateAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertCount(self::nombreFormations,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");
    }
    
    public function testSortOnDateDesc(){
        $client = static::createClient();
        $client->request('GET', '/formations/tri/publishedAt/DESC');
        $this->assertSelectorTextContains('h5',
                'Eclipse n°8 : Déploiement');
    }

}
