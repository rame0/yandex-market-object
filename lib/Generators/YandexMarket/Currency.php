<?php


namespace rame0\Generators\YandexMarket;

/**
 * Class Currency
 * @package rame0\Generators\YandexMarket
 */
class Currency extends Item
{
    public static string $CUR_RUB = 'RUB';
    public static string $CUR_RUR = 'RUR';
    public static string $CUR_UAH = 'UAH';
    public static string $CUR_BYN = 'BYN';
    public static string $CUR_KZT = 'KZT';
    public static string $CUR_USD = 'USD';
    public static string $CUR_EUR = 'EUR';

    /**
     * Currency constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param YML $ymlDoc
     */
    public function writeToDoc(YML $ymlDoc)
    {
        $ymlDoc->startElement('currency');
        $ymlDoc->writeAttribute('id', $this->id);
        $ymlDoc->writeAttribute('rate', 1);
        $ymlDoc->endElement();
        $ymlDoc->flushChunkToFile();
    }
}