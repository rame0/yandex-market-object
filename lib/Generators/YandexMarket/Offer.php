<?php


namespace rame0\Generators\YandexMarket;

/**
 * Class Offer
 * @property false|mixed|string type
 * @property false|float|mixed|string oldprice
 * @property false|mixed|string url
 * @property false|mixed|string currencyId
 * @property false|mixed|string categoryId
 * @property false|mixed|string|string[] picture
 * @property false|mixed|string delivery
 * @property false|int|mixed|string local_delivery_cost
 * @property false|mixed|string|string[] description
 * @property bool|mixed|string available
 * @property false|mixed|string vendor
 * @package rame0\Generators\YandexMarket
 */
class Offer extends Item
{
    protected array $generalProperties = [
        'url' => '',
        'price' => '',
        'oldprice' => '',
        'currencyId' => '',
        'xCategory' => '',
        'categoryId' => [],
        'picture' => '',
        'delivery' => '',
        'local_delivery_cost' => '',
        'deliveryIncluded' => '',
        'name' => '',
        'orderingTime' => '',
        'aliases' => '',
        'additional' => [],
        'description' => '',
        'sales_notes' => '',
        'promo' => '',
        'manufacturer_warranty' => '',
        'country_of_origin' => '',
        'downloadable' => '',
    ];
    protected array $attributes = [
        'id' => '', 'type' => '', 'available' => true,
        'bid' => null
    ];
    protected array $privateProperties = [
        'typePrefix' => '', 'vendor' => '', 'vendorCode' => '',
        'model' => ''
    ];
    protected array $params = [];

    /**
     * Offer constructor.
     * @param string $id
     * @param string $name
     * @param float $price
     */
    public function __construct(string $id, string $name, float $price)
    {
        $this->attributes['id'] = $id;
        $this->generalProperties['name'] = $name;
        $this->generalProperties['price'] = $price;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function __set(string $name, $value): bool
    {
        if (isset($this->generalProperties[$name])) {
            $this->generalProperties[$name] = $value;
        } elseif (isset($this->attributes[$name])) {
            $this->attributes[$name] = $value;
        } elseif (isset($this->privateProperties[$name]) && $name == "additionalCats") {
            $this->privateProperties[$name][] = $value;
        } elseif (isset($this->privateProperties[$name])) {
            $this->privateProperties[$name] = $value;
        } else {
            return false;
        }
        return true;
    }

    /**
     * @param string $name
     * @return false|mixed
     */
    public function __get(string $name)
    {
        if (isset($this->generalProperties[$name])) {
            return $this->generalProperties[$name];
        } else if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        } else if (isset($this->privateProperties[$name])) {
            return $this->privateProperties[$name];
        } else {
            return false;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param ?string $unit
     */
    public function addParam(string $name, $value, string $unit = NULL)
    {
        $this->params[] = [$name, $value, $unit];
    }

    /**
     * @param YML $ymlDoc
     */
    public function writeToDoc(YML $ymlDoc)
    {
        $ymlDoc->startElement('offer');

        foreach ($this->attributes as $name => $value) {
            if ($value !== 0 && empty($value)) {
                continue;
            }
            if (is_bool($value)) {
                $ymlDoc->writeAttribute($name, $value === true ? 'true' : 'false');
            } elseif (strlen(trim($value)) > 0) {
                $ymlDoc->writeAttribute($name, $ymlDoc->replaceSpecialChars(trim($value)));
            }
        }

        $this->writeProps($ymlDoc, $this->generalProperties);
        $this->writeProps($ymlDoc, $this->privateProperties);
        $this->writeParams($ymlDoc);

        $ymlDoc->endElement();

        $ymlDoc->flushChunkToFile();
    }

    protected function writeProps(YML $ymlDoc, array $prop)
    {
        if (!empty($prop)) {
            foreach ($prop as $key => $value) {
                if ($key === "additionalCats") {
                    foreach ($value as $catId) {
                        $ymlDoc->writeElement('categoryId', $catId);
                    }
                } elseif (is_array($value)) {
                    $this->writeProps($ymlDoc, $value);
                } elseif (is_bool($value)) {
                    $ymlDoc->writeElement($key, $value === true ? 'true' : 'false');
                } elseif (strlen(trim($value)) > 0) {
                    $ymlDoc->writeElement($key, $ymlDoc->replaceSpecialChars(trim($value)));
                }
            }
        }

        $ymlDoc->flushChunkToFile();
    }

    /**
     * @param YML $ymlDoc
     */
    protected function writeParams(YML $ymlDoc)
    {
        if (is_array($this->params) && count($this->params) > 0) {
            foreach ($this->params as $value) {
                if (strlen(trim($value[0])) > 0) {
                    $ymlDoc->startElement('param');
                    $ymlDoc->writeAttribute('name', $value[0]);
                    if (!empty($value[1])) {
                        $ymlDoc->writeAttribute('unit', $value[2]);
                    }
                    $ymlDoc->text($ymlDoc->replaceSpecialChars($value[1]));
                    $ymlDoc->endElement();
                }
            }
        }
    }
}