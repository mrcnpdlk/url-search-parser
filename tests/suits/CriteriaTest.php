<?php

namespace Tests\Mrcnpdlk\Lib\UrlSearchParser;

use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;
use PHPUnit\Framework\TestCase;

/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 23:38
 */
class CriteriaTest extends TestCase
{
    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testFilterParam(): void
    {
        $oFilterParam = new FilterParam('foo', Filter::PARAM_EQ, 1);
        $this->assertEquals('foo', $oFilterParam->param);
        $this->assertEquals(Filter::PARAM_EQ, $oFilterParam->operator);
        $this->assertEquals('=', $oFilterParam->sqlOperator);
        $this->assertEquals(1, $oFilterParam->value);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LT, 1);
        $this->assertEquals('<', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LTE, 1);
        $this->assertEquals('<=', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_GT, 1);
        $this->assertEquals('>', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_GTE, 1);
        $this->assertEquals('>=', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_IN, 1);
        $this->assertEquals(null, $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereIn());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_NOTIN, 1);
        $this->assertEquals(null, $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereNotIn());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LIKE, 1);
        $this->assertEquals('like', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereLike());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_NULL, null);
        $this->assertEquals(null, $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereNull());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_NOTNULL, null);
        $this->assertEquals(null, $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereNotNull());
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testSortParam(): void
    {
        $oSortParam = new SortParam('foo', 'asc');
        $this->assertEquals('foo', $oSortParam->param);
        $this->assertEquals(Sort::DIRECTION_ASC, $oSortParam->direction);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testSortParamInvalidDirection(): void
    {
        $this->expectException(InvalidParamException::class);
        $oSortParam = new SortParam('foo', 'somedirection');
    }
}
