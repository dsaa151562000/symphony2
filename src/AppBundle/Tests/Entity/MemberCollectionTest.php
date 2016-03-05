<?php

namespace AppBundle\Entity;



class MemberCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test */

    public function 指定した属性のメンバーが追加されていること() {

        $memberCollection = new MemberCollection();


        $this->assertThat($memberCollection->count(), $this->equalTo(0),
            '初期状態のコレクションの要素数が0');
    }

}
