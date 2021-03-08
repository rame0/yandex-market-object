<?php

namespace rame0\Generators\YandexMarket;

use XMLWriter;

class YML extends XMLWriter
{
    protected string $_output_file = '';
    protected string $_name = '';
    protected string $_company = '';
    protected string $_url = '';
    protected array $_delivery_options = [];
    protected ?int $_date = null;

    protected bool $_isFileOutput = false;
    protected bool $_isOutputOnTheFly = false;

    private bool $_isLastChunk = false;
    private int $_flush_every = 100;
    private int $_chunk_counter = 0;

    /**
     * @var array|string[]
     */
    private array $specialChars = ['&', '"', '>', '<', '`'];
    /**
     * @var array|string[]
     */
    private array $specialReplace = ['&amp;', '&quot;', '&gt;', '&lt;', '&apos;'];


    /**
     * YML constructor.
     * @param string $shop_name
     * @param string $company_name
     * @param string $shop_url
     */
    public function __construct(string $shop_name, string $company_name, string $shop_url)
    {

        $this->_name = $shop_name;
        $this->_company = $company_name;
        $this->_url = $shop_url;
        $this->openMemory();
    }

    /**
     *
     */
    public function startYML()
    {
        $this->_isLastChunk = false;
        $this->_chunk_counter = 0;
        if ($this->_isFileOutput) {
            file_put_contents($this->_output_file, "");
        }
        $this->startDocument('1.0', 'UTF-8');
        $this->writeDtd('yml_catalog', null, 'shops.dtd');
        $this->startElement('yml_catalog');

        if ($this->_date == null) {
            $this->_date = time();
        }
        $this->writeAttribute('date', date(DATE_ATOM, $this->_date));
        $this->startElement('shop');
        $this->writeElement('name', $this->_name);
        $this->writeElement('company', $this->_company);
        $this->writeElement('url', $this->_url);
        if (!empty($this->_delivery_options)) {
            $this->startElement('delivery-options');
            foreach ($this->_delivery_options as $option) {
                $this->startElement('option');
                $this->writeAttribute('cost', $option[0]);
                $this->writeAttribute('days', $option[1]);
                $this->endElement();
            }
            $this->endElement();
        }
    }

    /**
     *
     */
    public function endYML()
    {
        $this->endElement();
        $this->endElement();
        $this->endDocument();

        $this->_isLastChunk = true;
        $this->flushChunkToFile();
    }

    /**
     * @param Item $item
     */
    public function writeItem(Item $item)
    {
        $item->writeToDoc($this);
    }

    /**
     */
    public function flushChunkToFile()
    {
        if (!$this->_isFileOutput && !$this->_isOutputOnTheFly) {
            return;
        }
        $this->_chunk_counter++;
        if ($this->_isLastChunk || $this->_chunk_counter == $this->_flush_every) {
            $this->flush();
            $this->_chunk_counter = 0;
        }
    }

    /**
     * @param bool $empty
     * @return mixed
     */
    public function flush($empty = true)
    {
        if ($this->_isFileOutput) {
            return file_put_contents($this->_output_file, parent::flush($empty), FILE_APPEND);
        } else {
            return parent::flush($empty);
        }
    }

    public function replaceSpecialChars($string)
    {
        return str_replace($this->specialChars, $this->specialReplace, $string);
    }

    /**
     * @param string $char
     * @param string $replace
     */
    public function addSpecialChars(string $char, string $replace): void
    {
        $this->specialChars[] = $char;
        $this->specialReplace[] = $replace;
    }

    /**
     * @return int|null
     */
    public function getDate(): ?int
    {
        return $this->_date;
    }

    /**
     * @param int $_date
     */
    public function setDate(int $_date): void
    {
        $this->_date = $_date;
    }

    /**
     * @return array
     */
    public function getDeliveryOptions(): array
    {
        return $this->_delivery_options;
    }

    /**
     * @param array $delivery_option [<cost>, <delivery_days>]
     */
    public function addDeliveryOption(array $delivery_option): void
    {
        $this->_delivery_options[] = $delivery_option;
    }

    /**
     * @return string
     */
    public function getOutputFile(): string
    {
        return $this->_output_file;
    }

    /**
     * @param string $output_file
     */
    public function setOutputFile(string $output_file): void
    {
        $this->_output_file = $output_file;
        $this->_isFileOutput = true;
    }

    /**
     * @return bool
     */
    public function isIsFileOutput(): bool
    {
        return $this->_isFileOutput;
    }

    /**
     * @param bool $isFileOutput
     */
    public function setIsFileOutput(bool $isFileOutput): void
    {
        $this->_isFileOutput = $isFileOutput;
    }

    /**
     * @return bool
     */
    public function isIsOutputOnTheFly(): bool
    {
        return $this->_isOutputOnTheFly;
    }

    /**
     * @param bool $isOutputOnTheFly
     */
    public function setIsOutputOnTheFly(bool $isOutputOnTheFly): void
    {
        $this->_isOutputOnTheFly = $isOutputOnTheFly;
    }

    /**
     * @return int
     */
    public function getFlushEvery(): int
    {
        return $this->_flush_every;
    }

    /**
     * @param int $number_of_chunks
     */
    public function setFlushEvery(int $number_of_chunks): void
    {
        $this->_flush_every = $number_of_chunks;
    }
}