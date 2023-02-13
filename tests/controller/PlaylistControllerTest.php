<?php

namespace App\tests\controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Description of FormationControllerTest
 *
 * @author Maxat
 */
class PlaylistControllerTest extends WebTestCase{
    
    private const nombrePlaylists = 27;
    
    public function testSortOnTitleAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertCount(self::nombrePlaylists,$crawler->filter('h5'));
        $this->assertSelectorTextContains('h5',
                'Bases de la programmation (C#)');
    }
    
    public function testSortOnTitleDesc(){
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/DESC');
        $this->assertSelectorTextContains('h5',
                'Visual Studio 2019 et C#');
    }

}
