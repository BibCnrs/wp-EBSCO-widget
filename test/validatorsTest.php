<?php

class ValidatorsTest extends PHPUnit_Framework_TestCase
{
    public function testValidatorsUrlReturnUrlIfValid()
    {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'validators.php';
        $result = $validators['url']('http://localhost');
        $this->assertEquals($result, 'http://localhost');
    }

    /**
     * @expectedException        Exception
     * @expectedExceptionMessage EbscoWidget: L'url est invalide.
     */
    public function testValidatorsThrowErrorIfInvalid()
    {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'validators.php';
        $validators['url']('localhost');
    }

}
