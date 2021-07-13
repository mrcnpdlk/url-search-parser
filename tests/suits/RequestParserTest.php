<?php

namespace Tests\Mrcnpdlk\Lib\UrlSearchParser;

use InvalidArgumentException;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;
use Mrcnpdlk\Lib\UrlSearchParser\RequestParser;
use PHPUnit\Framework\TestCase;

/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 23:37
 */
class RequestParserTest extends TestCase
{
    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testEmptyParamException(): void
    {
        $this->expectException(EmptyParamException::class);
        $url   = 'https://api.expample.com?sort=,';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testEmptyParamException2(): void
    {
        $this->expectException(EmptyParamException::class);
        $url   = 'https://api.expample.com?sort=-';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testFilterAppendParam(): void
    {
        $url     = 'https://api.expample.com?'
            . 'filter[isFoo][eq]=1';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $oParser->getFilter()->appendParam(new FilterParam('isFoo', 'eq', 0));
        self::assertCount(2, $oParser->getFilter()->getByParam('isFoo'));
        $tParams = [];
        parse_str($query, $tParams);
        self::assertSame(http_build_query($tParams), $oParser->getQuery());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testFilterRemoveParam(): void
    {
        $url     = 'https://api.expample.com?'
            . 'filter[isFoo][eq]=1'
            . '&filter[isFoo][lt]=2'
            . '&filter[isBar][eq]=3';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $removedFilter = $oParser->getFilter()->removeByParam('isFoo');
        self::assertCount(1, $oParser->getFilter());
        self::assertCount(2, $removedFilter);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testFilterReplaceParam(): void
    {
        $url     = 'https://api.expample.com?'
            . 'filter[isFoo][eq]=1'
            . '&filter[isFoo][lt]=1'
            . '&filter[isBar][lt]=1'
        ;
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $oParser->getFilter()->replaceParam(new FilterParam('isFoo', 'eq', 0));
        self::assertCount(3, $oParser->getFilter());

        $oParser->getFilter()->replaceParam(new FilterParam('bar', 'eq', 'bar'));
        self::assertCount(4, $oParser->getFilter());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidParamException(): void
    {
        $this->expectException(InvalidParamException::class);
        $url   = 'https://api.expample.com?filter=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidParamExceptionEmptyOperator(): void
    {
        $this->expectException(InvalidParamException::class);
        $url   = 'https://api.expample.com?filter[foo][]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidParamExceptionEmptyParam(): void
    {
        $this->expectException(InvalidParamException::class);
        $url   = 'https://api.expample.com?filter[][eq]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidParamExceptionInvalidLimit(): void
    {
        $this->expectException(InvalidParamException::class);
        $url   = 'https://api.expample.com?limit=-1';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidParamExceptionInvalidOffset(): void
    {
        $this->expectException(InvalidParamException::class);
        $url   = 'https://api.expample.com?offset=-1';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidParamExceptionInvalidOperator(): void
    {
        $this->expectException(InvalidParamException::class);
        $url   = 'https://api.expample.com?filter[foo][qwer]=3';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidParamExceptionInvalidPage(): void
    {
        $this->expectException(InvalidParamException::class);
        $url   = 'https://api.expample.com?page=-1';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testInvalidSortDuplicateException(): void
    {
        $this->expectException(DuplicateParamException::class);
        $url   = 'https://api.expample.com?sort=a,-a';
        $query = parse_url($url, PHP_URL_QUERY);
        new RequestParser($query);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testParseAddons(): void
    {
        $url     = 'https://api.expample.com?foo=bar&baz=5';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        self::assertEquals('bar', $oParser->getQueryParam('foo', 'string'));
        self::assertEquals(5, $oParser->getQueryParam('baz', 'int'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testParseAddonsArrayAsArray(): void
    {
        $url     = 'https://api.expample.com?foo[]=1&foo[]=2&foo[]=3';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        self::assertEquals('1,2,3', $oParser->getQueryParam('foo', 'string'));
        self::assertEquals(['1', '2', '3'], $oParser->getQueryParam('foo', 'array'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testParseAddonsArrayAsString(): void
    {
        $url     = 'https://api.expample.com?foo=1,2,3';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        self::assertEquals('1,2,3', $oParser->getQueryParam('foo', 'string'));
        self::assertEquals(['1', '2', '3'], $oParser->getQueryParam('foo', 'array'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testParseAddonsAsBoolFalse(): void
    {
        foreach (['0', 'false', 'FALSE', ''] as $value) {
            $url     = 'https://api.expample.com?foo=' . $value;
            $query   = parse_url($url, PHP_URL_QUERY);
            $oParser = new RequestParser($query);

            self::assertEquals(false, $oParser->getQueryParam('foo', 'bool'));
        }
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testParseAddonsAsBoolTrue(): void
    {
        foreach (['1', 'true', 'TRUE', 'foobar'] as $value) {
            $url     = 'https://api.expample.com?foo=' . $value;
            $query   = parse_url($url, PHP_URL_QUERY);
            $oParser = new RequestParser($query);

            self::assertEquals(true, $oParser->getQueryParam('foo', 'bool'));
        }
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testParseAddonsInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $url     = 'https://api.expample.com?foo=bar&baz=5';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        self::assertEquals('bar', $oParser->getQueryParam('foo', 'stringa'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testParseFilter(): void
    {
        $url      = 'https://api.expample.com?'
            . 'filter[isFoo][eq]=1&'
            . 'filter[age][gt]=12&'
            . 'filter[type][in]=21,22,23&'
            . 'filter[bar]=baz&'
            . 'filter[baz][notnull]&'
            . 'filter[foo][null]&'
            . 'filter[foo][not]=2';
        $query    = parse_url($url, PHP_URL_QUERY);
        $oParser  = new RequestParser($query);
        $tFilters = $oParser->getFilter()->toArray();
        self::assertCount(7, $tFilters);
        self::assertEquals([21, 22, 23], $tFilters[2]->value);
        self::assertEquals('bar', $tFilters[3]->param);
        self::assertEquals(Filter::PARAM_EQ, $tFilters[3]->operator);
        self::assertEquals('baz', $tFilters[3]->value);
        self::assertTrue($tFilters[4]->isWhereNotNull());
        self::assertFalse($tFilters[4]->isWhereNull());
        self::assertTrue($tFilters[5]->isWhereNull());
        self::assertFalse($tFilters[5]->isWhereNotNull());
        self::assertTrue($tFilters[6]->isWhereNot());

        self::assertEquals([], $oParser->getSort()->toArray());
        self::assertNull($oParser->getLimit());
        self::assertNull($oParser->getPage());
        self::assertNull($oParser->getPhrase());
        self::assertNull($oParser->getOffset());

        self::assertCount(1, $oParser->getFilter()->getByParam('isFoo'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testParseSort(): void
    {
        $url      = 'https://api.expample.com?sort=id,-name';
        $query    = parse_url($url, PHP_URL_QUERY);
        $oParser  = new RequestParser($query);
        $tSorters = $oParser->getSort()->toArray();
        self::assertEquals('id', $tSorters[0]->param);
        self::assertEquals(Sort::DIRECTION_ASC, $tSorters[0]->direction);
        self::assertEquals('name', $tSorters[1]->param);
        self::assertEquals(Sort::DIRECTION_DESC, $tSorters[1]->direction);
        self::assertEquals([], $oParser->getFilter()->toArray());
        self::assertNull($oParser->getLimit());
        self::assertNull($oParser->getPage());
        self::assertNull($oParser->getPhrase());
        self::assertNull($oParser->getOffset());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testPhraseNull(): void
    {
        $url     = 'https://api.expample.com?phrase=&foo=baz';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        self::assertEquals(null, $oParser->getPhrase());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testRemoveQueryParam(): void
    {
        $url     = 'https://api.expample.com?foo=bar';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        self::assertEquals('bar', $oParser->getQueryParam('foo'));
        $oParser->removeQueryParam('foo');
        self::assertEquals(null, $oParser->getQueryParam('foo'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testRemoveQueryParamInvalid(): void
    {
        $this->expectException(InvalidParamException::class);
        $url     = 'https://api.expample.com?phrase=bar';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $oParser->removeQueryParam('phrase');
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     */
    public function testSetQueryParam(): void
    {
        $url     = 'https://api.expample.com?foo=bar';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        $oParser->setQueryParam('foo', 'baz');
        self::assertEquals('baz', $oParser->getQueryParam('foo'));
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testSortReplaceParam(): void
    {
        $url     = 'https://api.expample.com?'
            . 'sort=a,-b';
        $query   = parse_url($url, PHP_URL_QUERY);
        $oParser = new RequestParser($query);

        self::assertCount(1, $oParser->getSort()->getByParamName('a'));
        self::assertSame(Sort::DIRECTION_ASC, $oParser->getSort()->getByParamName('a')[0]->direction);

        $oParser->getSort()->replaceParam(new SortParam('a', Sort::DIRECTION_DESC));
        self::assertCount(2, $oParser->getSort());
        self::assertCount(1, $oParser->getSort()->getByParamName('a'));
        self::assertSame(Sort::DIRECTION_DESC, $oParser->getSort()->getByParamName('a')[0]->direction);

        $oParser->getSort()->appendParam(new SortParam('c', Sort::DIRECTION_ASC));
        self::assertCount(3, $oParser->getSort());
        self::assertCount(1, $oParser->getSort()->getByParamName('c'));
        self::assertSame(Sort::DIRECTION_ASC, $oParser->getSort()->getByParamName('c')[0]->direction);

        self::assertTrue($oParser->getSort()->isExists('a'));
        self::assertTrue($oParser->getSort()->isExists('b'));
        self::assertTrue($oParser->getSort()->isExists('c'));
        self::assertFalse($oParser->getSort()->isExists('d'));
    }
}
