<?php

namespace Tests\Mrcnpdlk\Lib\UrlSearchParser;

use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;
use PHPUnit\Framework\TestCase;

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 18.01.2019
 * Time: 11:04
 */
class FilterTest extends TestCase
{
    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function testInvalidFilterConstructor(): void
    {
        $this->expectException(InvalidParamException::class);
        new Filter('aaa');
    }
}
