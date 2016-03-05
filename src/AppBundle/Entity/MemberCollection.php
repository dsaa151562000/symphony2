<?php
/**
 * Created by PhpStorm.
 * User: s
 * Date: 2016/03/03
 * Time: 11:21
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class MemberCollection extends ArrayCollection
{

    //複数の団員情報を保持する コレクションクラス

    //しかし、団員情報は、データベースに格納するわけではないので、
    //Doctrine と連携するようなリポジトリオブジェクトは使えません。
    //今回は、複数の団員情報が格納された団員一覧のオブ ジェクト、
    //つまりコレクションオブジェクトを作り、サービスとしてサービスコンテナに登録しておきます

    public function addMember($name, $part, $joinedDate)
    {
        $member = new Member($name, $part, $joinedDate);
        $this->add($member);
    }
}