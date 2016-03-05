<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TopppageControllerTest extends WebTestCase
{

    /**
     * @test */

    public function 画面が表示される() {

        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($crawler->filter('html:contains("歴史と調和するシンフォニー、音楽で未来へつなぐ")')->count() > 0);

    }

}
