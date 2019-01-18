<?php

use mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam;
use mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 18.01.2019
 * Time: 11:02
 */

class FilterParamTest extends \mrcnpdlk\Lib\UrlSearchParser\TestCase
{
    /**
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidConstructor(): void
    {
        $this->expectException(InvalidParamException::class);
        new FilterParam('foo', 'invalid', null);
    }
}
