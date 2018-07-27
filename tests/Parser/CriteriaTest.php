<?php

use mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam;
use mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam;

/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 23:38
 */
class CriteriaTest extends \mrcnpdlk\Lib\UrlSearchParser\TestCase
{
    public function testFilterParam()
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

    public function testSortParam()
    {
        $oSortParam = new SortParam('foo', 'asc');
        $this->assertEquals('foo', $oSortParam->param);
        $this->assertEquals('asc', $oSortParam->direction);
    }

}
