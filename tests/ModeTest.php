<?php

namespace Pcelta\VirtualStream;

use PHPUnit\Framework\TestCase;

class ModeTest extends TestCase
{
    /**
     * @dataProvider invalidModeProvider
     * @expectedException \Pcelta\VirtualStream\ModeNotAllowedException
     * @expectedExceptionMessage Mode given is not allowed:
     */
    public function testConstructShouldThrowModeNotAllowedExceptionWhenInvalidModeIsGiven($invalidMode)
    {
        new Mode($invalidMode);
    }

    /**
     * @dataProvider invalidModeProvider
     * @expectedException \Pcelta\VirtualStream\ModeNotAllowedException
     * @expectedExceptionMessage Mode given is not allowed:
     */
    public function testSetModeShouldThrowModeNotAllowedExceptionWhenInvalidModeIsGiven($invalidMode)
    {
        new Mode($invalidMode);
    }

    public static function invalidModeProvider()
    {
        return [
            [''],
            ['blabla'],
            ['z'],
            ['+'],
        ];
    }

    /**
     * @dataProvider notReadingModesProvider
     */
    public function testIsReadingShouldReturnFalseWhenModeIsNotReading($notReadingMode)
    {
        $mode = new Mode($notReadingMode);
        $result = $mode->isReading();
        $this->assertFalse($result);
    }

    public static function notReadingModesProvider()
    {
        return [
            ['w'],
            ['a'],
            ['c'],
            ['x'],
        ];
    }

    /**
     * @dataProvider readingModesProvider
     */
    public function testIsReadingShouldReturnTrueWhenModeIsReading($readingMode)
    {
        $mode = new Mode($readingMode);
        $result = $mode->isReading();
        $this->assertTrue($result);
    }

    public static function readingModesProvider()
    {
        return [
            ['r'],
            ['r+'],
            ['w+'],
            ['x+'],
            ['c+'],
            ['a+'],
        ];
    }

    public function testIsWritingShouldReturnFalseWhenModeIsNotWriting()
    {
        $mode = new Mode('r');
        $result = $mode->isWriting();
        $this->assertFalse($result);
    }

    /**
     * @dataProvider writingModesProvider
     */
    public function testIsWritingShouldReturnTrueWhenModeIsWriting($writingMode)
    {
        $mode = new Mode($writingMode);
        $result = $mode->isWriting();
        $this->assertTrue($result);
    }

    public static function writingModesProvider()
    {
        return [
            ['r+'],
            ['w'],
            ['w+'],
            ['a'],
            ['a+'],
            ['c'],
            ['c+'],
            ['x'],
            ['x+'],
        ];
    }

}

