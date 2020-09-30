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
        self::assertEquals('foo', $oFilterParam->param);
        self::assertEquals(Filter::PARAM_EQ, $oFilterParam->operator);
        self::assertEquals('=', $oFilterParam->sqlOperator);
        self::assertEquals(1, $oFilterParam->value);
        self::assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LT, 1);
        self::assertEquals('<', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LTE, 1);
        self::assertEquals('<=', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_GT, 1);
        self::assertEquals('>', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_GTE, 1);
        self::assertEquals('>=', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhere());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_IN, 1);
        self::assertEquals(null, $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereIn());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_NOTIN, 1);
        self::assertEquals(null, $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereNotIn());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LIKE, 1);
        self::assertEquals('like', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereLike());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LIKE_LEFT, 1);
        self::assertEquals('like', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereLikeLeft());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_LIKE_RIGHT, 1);
        self::assertEquals('like', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereLikeRight());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_NULL, null);
        self::assertEquals(null, $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereNull());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_NOTNULL, null);
        self::assertEquals(null, $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereNotNull());

        $oFilterParam = new FilterParam('foo', Filter::PARAM_REGEXP, null);
        self::assertEquals('regexp', $oFilterParam->sqlOperator);
        self::assertEquals(true, $oFilterParam->isWhereRegexp());
    }

    public function testSortParam(): void
    {
        $oSortParam = new SortParam('foo', 'asc');
        self::assertEquals('foo', $oSortParam->param);
        self::assertEquals(Sort::DIRECTION_ASC, $oSortParam->direction);
    }

    public function testSortParamInvalidDirection(): void
    {
        $this->expectException(InvalidParamException::class);
        $oSortParam = new SortParam('foo', 'somedirection');
    }
}
