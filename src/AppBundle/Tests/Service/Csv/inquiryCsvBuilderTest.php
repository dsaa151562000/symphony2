<?php


namespace AppBundle\Service\Csv;

use AppBundle\Entity\Inquiry;
use AppBundle\Entity\InquiryRepository;
use Doctrine\Common\Collections\ArrayCollection;

class inquiryCsvBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var InquiryCsvBuilder
    */
    private $SUT;

    /**
     * @var InquiryRepository
     */
    private $inquiryRepository;

    /**
     * @test
     */

    public function CSVが正しく作られること() {

     }

}