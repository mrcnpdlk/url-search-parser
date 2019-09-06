<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 19:21
 */
declare(strict_types=1);

namespace Mrcnpdlk\Lib\UrlSearchParser\Criteria;

use Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;

/**
 * Class SortParam
 */
class SortParam
{
    /**
     * @var string
     */
    public $param;
    /**
     * @var string
     */
    public $direction;

    /**
     * SortParam constructor.
     *
     * @param string $param
     * @param string $direction
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function __construct(string $param, string $direction)
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, [Sort::DIRECTION_ASC, Sort::DIRECTION_DESC], true)) {
            throw new InvalidParamException(sprintf('Invalid direction type `%s`. Only %s is allowed', $direction,
                implode(',', [Sort::DIRECTION_ASC, Sort::DIRECTION_DESC])));
        }
        $this->param     = $param;
        $this->direction = $direction;
    }
}
