<?php

class TranslatorTest extends \PHPUnit_Framework_TestCase
{

    const VALID_TRANSLATION_TEXT = '{0}zero||{1}one||{2,4}two to four||{5,Inf}five to infinity';
    const INVALID_TRANSLATION_TEXT = '{}none||{Inf}infinity||';

    /**
     * Test that ensures that the correct part of the translation string (possibly all of it) is returned when a
     * value (valid or invalid) is provided.
     * (Incorrect/Invalid values should result in the whole string being returned)
     *
     * @param string $translation - the value from the translation file
     * @param mixed $value - the value being passed for pluralisation
     * @param string $expected - expected response
     * @dataProvider validTranslationDataProvider
     */
    public function testValidTranslationReturnsCorrectValue($translation, $value, $expected)
    {
        $value = Translator::getInstance()->getPluralFromNumber($translation , $value);
        $this->assertEquals($value,$expected);
    }

    /**
     * Test that ensures that the whole translation string is returned when an invalid translation
     * value is used regardless of the value provided
     *
     * @param string $translation - the value from the translation file
     * @param mixed $value - the value being passed for pluralisation
     * @param string $expected - expected response
     * @dataProvider invalidTranslationDataProvider
     */
    public function testInvalidTranslationReturnsWholeString($translation, $value, $expected)
    {
        $value = Translator::getInstance()->getPluralFromNumber($translation , $value);
        $this->assertEquals($value, $expected);
    }

    /**
     * Provides data comparing valid translation to various values;
     * @return array
     */
    public function validTranslationDataProvider()
    {

        return array(
            array(self::VALID_TRANSLATION_TEXT , 0,'zero'),
            array(self::VALID_TRANSLATION_TEXT, 1, 'one'),
            array(self::VALID_TRANSLATION_TEXT, 2, 'two to four'),
            array(self::VALID_TRANSLATION_TEXT, 3, 'two to four'),
            array(self::VALID_TRANSLATION_TEXT, 4, 'two to four'),
            array(self::VALID_TRANSLATION_TEXT, 5, 'five to infinity'),
            array(self::VALID_TRANSLATION_TEXT, 999999999999, 'five to infinity'),
            array(self::VALID_TRANSLATION_TEXT, 'Inf', self::VALID_TRANSLATION_TEXT),
            array(self::VALID_TRANSLATION_TEXT, 'Text', self::VALID_TRANSLATION_TEXT),
            array(self::VALID_TRANSLATION_TEXT, '', self::VALID_TRANSLATION_TEXT),
            array(self::VALID_TRANSLATION_TEXT, -1, self::VALID_TRANSLATION_TEXT),
            array(self::VALID_TRANSLATION_TEXT, 0.6, self::VALID_TRANSLATION_TEXT)
        );
    }

    /**
     * Provides data comparing invalid translation to various values;
     * @return array
     */
    public function invalidTranslationDataProvider()
    {

        return array(
            array(self::INVALID_TRANSLATION_TEXT, 0, self::INVALID_TRANSLATION_TEXT),
            array(self::INVALID_TRANSLATION_TEXT, 1, self::INVALID_TRANSLATION_TEXT),
            array(self::INVALID_TRANSLATION_TEXT, 999999, self::INVALID_TRANSLATION_TEXT)
        );
    }

}
