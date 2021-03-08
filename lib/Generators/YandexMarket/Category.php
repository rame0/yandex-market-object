<?php


namespace rame0\Generators\YandexMarket;

use Exception;

/**
 * Class Category
 * @package rame0\Generators\YandexMarket
 */
class Category extends Item
{
    /**
     * @var int|null
     */
    private ?int $parentId = null;
    /**
     * @var string
     */
    private string $name;

    /**
     * Category constructor.
     * @param int $id
     * @param string $name
     * @param int|null $parent
     * @throws Exception
     */
    public function __construct(int $id, string $name, int $parent = null)
    {
        if ($id <= 0) {
            throw new Exception('Category id have to be integer > 0');
        } else {
            $this->id = $id;
        }

        if (empty(trim($name))) {
            throw new Exception('Category name is empty!');
        } else {
            $this->name = $name;
        }

        $this->parentId = $parent;
    }

    /**
     * @param YML $ymlDoc
     */
    public function writeToDoc(YML $ymlDoc)
    {
        $ymlDoc->startElement('category');
        $ymlDoc->writeAttribute('id', $this->id);
        if ($this->parentId !== null && intval($this->parentId) > 0) {
            $ymlDoc->writeAttribute('parentId', $this->parentId);
        }
        $ymlDoc->text($this->name);
        $ymlDoc->endElement();
        $ymlDoc->flushChunkToFile();
    }
}