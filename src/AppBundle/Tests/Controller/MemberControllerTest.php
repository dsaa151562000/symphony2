<?php
/**
 * Created by PhpStorm.
 * User: s
 * Date: 2016/03/05
 * Time: 14:47
 */

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class MemberControllerTest extends WebTestCase
{

    public function test団員情報画面が表示される()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/member');
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}