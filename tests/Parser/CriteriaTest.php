<?php

use mrcnpdlk\Lib\UrlQueryParser\Criteria\Filter;
use mrcnpdlk\Lib\UrlQueryParser\Criteria\FilterParam;
use mrcnpdlk\Lib\UrlQueryParser\Criteria\SortParam;

/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 23:38
 */
class CriteriaTest extends \mrcnpdlk\Lib\UrlQueryParser\TestCase
{
    public function testSortParam()
    {
        $oSortParam = new SortParam('foo', 'asc');
        $this->assertEquals('foo', $oSortParam->param);
        $this->assertEquals('asc', $oSortParam->direction);
    }

    public function testFilterParam()
    {
        $oFilterParam = new FilterParam('foo', Filter::PARAM_EQ,1);
        $this->assertEquals('foo', $oFilterParam->param);
        $this->assertEquals(Filter::PARAM_EQ, $oFilterParam->operator);
        $this->assertEquals('=', $oFilterParam->sqlOperator);
        $this->assertEquals(1, $oFilterParam->value);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LT,1);
        $this->assertEquals('<', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LTE,1);
        $this->assertEquals('<=', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_GT,1);
        $this->assertEquals('>', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_GTE,1);
        $this->assertEquals('>=', $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_IN,1);
        $this->assertEquals(null, $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereIn());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_NOTIN,1);
        $this->assertEquals(null, $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereNotIn());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LIKE,1);
        $this->assertEquals(null, $oFilterParam->sqlOperator);
        $this->assertEquals(true, $oFilterParam->isWhereLike());

    }

}
