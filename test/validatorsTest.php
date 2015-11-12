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

    public function testValidatorsPasswwordReturnUrlIfValid()
    {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'validators.php';
        $result = $validators['password']('1ts@Secret');
        $this->assertEquals($result, '1ts@Secret');
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    EbscoWidget: Le secret doit avoir au moins 8 characters avec au moins une capitale, un chiffre et un charactére spécial.
     */
    public function testValidatorsThrowErrorIfInvalidLength()
    {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'validators.php';
        $validators['password']('1ts@S');
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    EbscoWidget: Le secret doit avoir au moins 8 characters avec au moins une capitale, un chiffre et un charactére spécial.
     */
    public function testValidatorsThrowErrorIfNoUpperCase()
    {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'validators.php';
        $validators['password']('1ts@secret');
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    EbscoWidget: Le secret doit avoir au moins 8 characters avec au moins une capitale, un chiffre et un charactére spécial.
     */
    public function testValidatorsThrowErrorIfNoDigit()
    {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'validators.php';
        $validators['password']('its@Secret');
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    EbscoWidget: Le secret doit avoir au moins 8 characters avec au moins une capitale, un chiffre et un charactére spécial.
     */
    public function testValidatorsThrowErrorIfNoSpecialCharacter()
    {
        require dirname(__FILE__).DIRECTORY_SEPARATOR . '..'.DIRECTORY_SEPARATOR.'actions'.DIRECTORY_SEPARATOR.'validators.php';
        $validators['password']('itsaSecret');
    }
}
