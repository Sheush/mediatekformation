<?php

namespace App\tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AffichageTest
 *
 * @author Maxat
 */
class AffichageTest extends WebTestCase{
    
    public function testIndexPage(){
        $client = static::createClient();
        $client->request('GET', '');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
