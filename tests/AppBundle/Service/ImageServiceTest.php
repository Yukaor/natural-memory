<?php
/**
 * Created by PhpStorm.
 * User: utilisateur
 * Date: 05/03/17
 * Time: 13:57
 */

namespace Tests\AppBundle\Service;

class ImageServiceTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp();


    }

    public function testGetImageNature()
    {

        $this->setUp();

        //Valeur du retour de la fonction
        $aFixture = array
        (
          0 => "http://www.naturepicoftheday.com/npods/2010/may/black_eyed_beauty_full.jpg",
          1 => "http://www.naturepicoftheday.com/npods/2016/january/hope_full.jpg"
        );

        //Page Html de nature pic of the day
        $sFixtureHTML= "
<html>\n
   <head>\n
      <meta name=\"description\" content=\"\"/>\n
      <meta name=\"keywords\" content=\"\"/>\n
      <link rel=\"stylesheet\" href=\"/include/dark_style.css\" type=\"text/css\">\n
      <title> Nature Pic of the Day - Random NPODs</title>\n
      <script type=\"text/javascript\" src=\"/include/jquery-1.2.1.pack.js\"></script>\n
      <script type=\"text/javascript\" src=\"/include/js_favs.php\"></script>\n
     
   </head>\n
   <body bgcolor=#000000>\n
   <div id=BOX>\n
      \n
      <div id=TITLE><span class=TITLE_lnk><a href=http://www.naturepicoftheday.com>Nature Pic of the Day</a></span></div>\n
\n
      <div id='WELCOME'>Welcome to Nature Pic of the Day!  See a new, gorgeous, picture of nature every day!\n
\n
         <BR>\n
\n
         Thank you for sharing and supporting the site by clicking through\n
          the Amazon banner on the Support the Site page.  Your support keeps us going!\n
\n
      </div>\n
\n
      <div id=MNU>\n
         <div id=LEFT_MNU><span class=MNULNK>\n
            <a href=/support>Support the Site</a>\n
         </span></div>\n
         <div class='dropdown'><span class=MNULNK><button class='dropbtn'>Information</button>\n
            <div class='dropdown-content'>\n
            <a href=/about>About</a>\n
            <a href=/submit>Submit</a>\n
         </span></div></div>\n
         <div class='dropdown'><span class=MNULNK><button class='dropbtn'>More NPODs</button>\n
            <div class='dropdown-content'>\n
            <a href=/archive>Archive</a>\n
            <a href=/random>Random</a>\n
            <a href=/search>Search</a>\n
         </span></div></div>\n
         <div class='dropdown'><span class=MNULNK><button class='dropbtn'>Top 10</button>\n
            <div class='dropdown-content'>\n
            <a href=/favorite_npods>Favorites</a>\n
            <a href=/most_viewed>Most Viewed</a>\n
         </span></div></div>\n
         <div id=LEFT_MNU><span class=MNULNK>\n
            <a href=/recent_comments>Recent Comments</a>\n
         </span></div>\n
               </div id=MNU>\n
      <div class=DATE>\n
         <span class=\"ARK_DATES\"><a href=\"random\">Refresh Random NPODs</a></span>\n
      </div>\n
      <div id=RANDOM>\n
      <div class=RANDOM_NPOD>\n
      <div class=RANDOM_TXT><span class=NAVLNK_ARK><a href=/archive/2016-09-12>September 12, 2016</a></span></div>\n
      <div class=RANDOM_THUMB>\n
      <span class=PLAIN_LNK><a href=/archive/2016-09-12 title='2016-09-12: Repurposed'>\n
      <img src='npods/2010/may/black_eyed_beauty_full.jpg\"' border=1 alt='Butchart Gardens in British Columbia, CA' width=146px></a></span></div>
      <div class=RANDOM_NPOD>\n
      <div class=RANDOM_TXT><span class=NAVLNK_ARK><a href=/archive/2013-09-28>September 28, 2013</a></span></div>\n
      <div class=RANDOM_THUMB>\n
      <span class=PLAIN_LNK><a href=/archive/2013-09-28 title='2013-09-28: Mama Duck and Babies'>\n
      <img src='npods/2016/january/hope_full.jpg' border=1 alt='Ducks in Florida' width=146px></a></span></div>\n
         </div></body></html>";

        //résultat après le extract sur le crawler
        $aFixtureXpath = [
            0 => '/npods/2010/may/black_eyed_beauty_full.jpg',
            1 => '/npods/2016/january/hope_full.jpg',
        ];

        //Objet Mock Imageservice
        $oInstance = $this->getMockBuilder('AppBundle\Service\ImageService')
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        //Objet mock Crawler
        $oCrawler = $this->getMockBuilder('Symfony\Component\DomCrawler\Crawler')
                        ->disableOriginalClone()
                        ->disableArgumentCloning()
                        ->disallowMockingUnknownTypes()
                        ->setConstructorArgs([$sFixtureHTML])
                        ->setMethods(['filterXpath','extract'])
                        ->getMock();

        $oCrawler->expects($this->once())
            ->method('filterXpath')
            ->with('//span[contains(@class, "PLAIN_LNK")]')
            ->willReturnSelf();

        $oCrawler->expects($this->once())
            ->method('filterXpath')
            ->with('//img')
            ->willReturnSelf();

        $oCrawler->expects($this->once())
            ->method('extract')
            ->with(array('src'))
            ->willReturn($aFixtureXpath);


        $this->assertEquals($aFixture,$oInstance->getimagenature(2));

        parent::tearDown();

    }
}