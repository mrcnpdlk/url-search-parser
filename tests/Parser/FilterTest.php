<?php

use mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 18.01.2019
 * Time: 11:04
 */

class FilterTest extends \mrcnpdlk\Lib\UrlSearchParser\TestCase
{
    /**
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidFilterConstructor(): void
    {
        $this->expectException(InvalidParamException::class);
        new Filter('aaa');
    }
}
