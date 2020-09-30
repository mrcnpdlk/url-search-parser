<?php

namespace Tests\Mrcnpdlk\Lib\UrlSearchParser;

use Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;
use PHPUnit\Framework\TestCase;

/**
 * Created by Marcin Pudełek <marcin@pudelek.org.pl>
 * Date: 18.01.2019
 * Time: 11:02
 */
class FilterParamTest extends TestCase
{
    public function testInvalidConstructor(): void
    {
        $this->expectException(InvalidParamException::class);
        new FilterParam('foo', 'invalid', null);
    }
}
