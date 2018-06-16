<?php

use mrcnpdlk\Lib\UrlQueryParser\Criteria\Sort;
use mrcnpdlk\Lib\UrlQueryParser\RequestParser;

/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 23:37
 */
class RequestParserTest extends \mrcnpdlk\Lib\UrlQueryParser\TestCase
{
    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     * @expectedException \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     */
    public function testEmptyParamException()
    {
        $url   = 'https://api.expample.com?sort=,';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     * @expectedException \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     */
    public function testEmptyParamException2()
    {
        $url   = 'https://api.expample.com?sort=-';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     * @expectedException \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    public function testInvalidParamException()
    {
        $url   = 'https://api.expample.com?filter=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     * @expectedException \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionEmptyOperator()
    {
        $url   = 'https://api.expample.com?filter[foo][]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     * @expectedException \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionEmptyParam()
    {
        $url   = 'https://api.expample.com?filter[][eq]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     * @expectedException \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    public function testInvalidParamExceptionInvalidOperator()
    {
        $url   = 'https://api.expample.com?filter[foo][qwer]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    public function testParseFilter()
    {
        $url      = 'https://api.expample.com?filter[isFoo][eq]=1&filter[age][gt]=12&filter[type][in]=21,22,23';
        $query    = parse_url($url, PHP_URL_QUERY);
        $oParser  = new RequestParser($query);
        $tFilters = $oParser->getFilter()->toArray();
        $this->assertEquals(3, \count($tFilters));
        $this->assertEquals([21, 22, 23], $tFilters[2]->value);


        $this->assertEquals([], $oParser->getSort()->toArray());
        $this->assertNull($oParser->getLimit());
        $this->assertNull($oParser->getPage());
        $this->assertNull($oParser->getPhrase());
        $this->assertNull($oParser->getOffset());
    }

    /**
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    public function testParseSort()
    {
        $url      = 'https://api.expample.com?sort=id,-name';
        $query    = parse_url($url, PHP_URL_QUERY);
        $oParser  = new RequestParser($query);
        $tSorters = $oParser->getSort()->toArray();
        $this->assertEquals('id', $tSorters[0]->param);
        $this->assertEquals(Sort::DIRECTION_ASC, $tSorters[0]->direction);
        $this->assertEquals('name', $tSorters[1]->param);
        $this->assertEquals(Sort::DIRECTION_DESC, $tSorters[1]->direction);
        $this->assertEquals([], $oParser->getFilter()->toArray());
        $this->assertNull($oParser->getLimit());
        $this->assertNull($oParser->getPage());
        $this->assertNull($oParser->getPhrase());
        $this->assertNull($oParser->getOffset());
    }

}
