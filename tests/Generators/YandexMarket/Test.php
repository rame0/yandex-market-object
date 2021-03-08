<?php

namespace rame0\Generators\YandexMarket\Tests;

use PHPUnit\Framework\TestCase;
use rame0\Generators\YandexMarket\Category;
use rame0\Generators\YandexMarket\Currency;
use rame0\Generators\YandexMarket\Offer;
use rame0\Generators\YandexMarket\YML;

class Test extends TestCase
{
    public function test_base(): void
    {
        $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd"><yml_catalog date="2021-03-08T17:20:28+00:00"><shop><name>MyShop</name><company>MyCorp</company><url>https://www.site.ru/</url></shop></yml_catalog>
';
        $yml_generator = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
        $yml_generator->setDate(strtotime('2021-03-08T17:20:28+00:00'));
        $yml_generator->startYML();
        $yml_generator->endYML();

        $actual_xml = $yml_generator->flush();
        $this->assertEquals($expected_xml, $actual_xml);
    }

    public function test_currencies()
    {
        $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd"><yml_catalog date="2021-03-08T17:20:28+00:00"><shop><name>MyShop</name><company>MyCorp</company><url>https://www.site.ru/</url><currencies><currency id="RUB" rate="1"/></currencies></shop></yml_catalog>
';
        $yml_generator = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
        $yml_generator->setDate(strtotime('2021-03-08T17:20:28+00:00'));
        $yml_generator->startYML();
        $yml_generator->startElement('currencies');
        $yml_generator->writeItem(new Currency(Currency::$CUR_RUB));
        $yml_generator->endElement();
        $yml_generator->endYML();

        $actual_xml = $yml_generator->flush();
        $this->assertEquals($expected_xml, $actual_xml);
    }

    public function test_categories()
    {
        $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd"><yml_catalog date="2021-03-08T17:20:28+00:00"><shop><name>MyShop</name><company>MyCorp</company><url>https://www.site.ru/</url><categories><category id="1">cat1</category><category id="2">cat2</category><category id="3" parentId="1">cat3</category></categories></shop></yml_catalog>
';
        $yml_generator = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
        $yml_generator->setDate(strtotime('2021-03-08T17:20:28+00:00'));
        $yml_generator->startYML();
        $yml_generator->startElement('categories');
        $yml_generator->writeItem(new Category(1, 'cat1'));
        $yml_generator->writeItem(new Category(2, 'cat2'));
        $yml_generator->writeItem(new Category(3, 'cat3', 1));
        $yml_generator->endElement();
        $yml_generator->endYML();

        $actual_xml = $yml_generator->flush();
        $this->assertEquals($expected_xml, $actual_xml);
    }

    public function test_simple_offer()
    {
        $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd"><yml_catalog date="2021-03-08T17:20:28+00:00"><shop><name>MyShop</name><company>MyCorp</company><url>https://www.site.ru/</url><offers><offer id="1" available="true"><price>1</price><name>cat1</name></offer></offers></shop></yml_catalog>
';
        $yml_generator = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
        $yml_generator->setDate(strtotime('2021-03-08T17:20:28+00:00'));
        $yml_generator->startYML();
        $yml_generator->startElement('offers');
        $yml_generator->writeItem(new Offer(1, 'cat1', 1));
        $yml_generator->endElement();
        $yml_generator->endYML();

        $actual_xml = $yml_generator->flush();
        $this->assertEquals($expected_xml, $actual_xml);
    }

    public function test_file()
    {
        $expected_xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd"><yml_catalog date="2021-03-08T17:20:28+00:00"><shop><name>MyShop</name><company>MyCorp</company><url>https://www.site.ru/</url><offers><offer id="1" available="true"><price>1</price><name>cat1</name></offer></offers></shop></yml_catalog>
';
        $yml_generator = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
        $yml_generator->setOutputFile('./test.yml');
        $yml_generator->setDate(strtotime('2021-03-08T17:20:28+00:00'));
        $yml_generator->startYML();
        $yml_generator->startElement('offers');
        $yml_generator->writeItem(new Offer(1, 'cat1', 1));
        $yml_generator->endElement();
        $yml_generator->endYML();

        $actual_xml = file_get_contents('./test.yml');

        $this->assertEquals($expected_xml, $actual_xml);
    }

//
//    public function test_file_on_the_fly()
//    {
//        $yml_generator = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
//
//        $yml_generator->setOutputFile('./test.yml', true);
//
//        $yml_generator->setIsOutputOnTheFly(true);
//        $yml_generator->setDate(strtotime('2021-03-08T17:20:28+00:00'));
//        $yml_generator->startYML();
//        $yml_generator->startElement('offers');
//        $yml_generator->setFlushEvery(100);
//        for ($i = 1; $i < 925; $i++) {
//            $offer = new Offer($i, 'cat' . $i, $i * 10);
//            $offer->type = "vendor.model";
//            $offer->oldprice = ceil($i * 10 + 100);
//            $offer->url = "https://www.site.ru/{$i}";
//            $offer->currencyId = Currency::$CUR_RUB;
//            $offer->categoryId = round(1 / 100);
//            $offer->picture = str_replace(' ', '-_-', 'https://www.site.ru/' . $i . '?x=600');
//            $offer->delivery = 'true';
//            $offer->local_delivery_cost = 500;
//            $offer->description = $yml_generator->replaceSpecialChars('Описание товара ' . $i);
//            $offer->available = true;
//            $offer->vendor = 'Вендор';
//            $offer->addParam('Форма', "Круг");
//            $offer->addParam('Материал', 'состав');
//            $offer->addParam("Высота", 10, "мм");
//            $offer->addParam("Плотность", 20);
//            $offer->addParam("Ширина", $i, 'м');
//            $offer->addParam("Длина", $i, "м");
//            $yml_generator->writeItem($offer);
//        }
//        $yml_generator->endElement();
//        $yml_generator->endYML();
//
//        $this->assertIsBool(true);
//    }
}
