<?php


namespace rame0\Generators\YandexMarket;


abstract class Item
{
    /** @var string */
    protected string $id;

    /**
     * Get item Id
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param YML $ymlDoc
     */
    abstract public function writeToDoc(YML $ymlDoc);
}