<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 19:17
 */
declare(strict_types=1);

namespace mrcnpdlk\Lib\UrlSearchParser\Criteria;

use mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;

/**
 * Class FilterParam
 *
 * @package mrcnpdlk\Lib\UrlSearchParser\Criteria
 */
class FilterParam
{
    /**
     * @var string
     */
    public $param;
    /**
     * @var string
     */
    public $operator;
    /**
     * @var string|null
     */
    public $sqlOperator;
    /**
     * @var mixed
     */
    public $value;

    /**
     * FilterParam constructor.
     *
     * @param string $param
     * @param string $operator
     * @param        $value
     *
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function __construct(string $param, string $operator, $value)
    {
        $this->param    = $param;
        $this->operator = strtolower(trim($operator));
        if (!array_key_exists($this->operator, Filter::$allowedOperators)) {
            throw new InvalidParamException(
                sprintf('Invalid operator "%s". Only one of "%s" is allowed',
                    $this->operator,
                    implode(',', array_keys(Filter::$allowedOperators))
                )
            );
        }
        $this->sqlOperator = Filter::$allowedOperators[$operator];
        $this->value       = $value;
    }

    /**
     * @return bool
     */
    public function isWhere(): bool
    {
        return \in_array($this->operator, [
            Filter::PARAM_EQ,
            Filter::PARAM_GT,
            Filter::PARAM_GTE,
            Filter::PARAM_LT,
            Filter::PARAM_LTE,
        ], true);
    }

    /**
     * @return bool
     */
    public function isWhereIn(): bool
    {
        return Filter::PARAM_IN === $this->operator;
    }

    /**
     * @return bool
     */
    public function isWhereLike(): bool
    {
        return Filter::PARAM_LIKE === $this->operator;
    }

    /**
     * @return bool
     */
    public function isWhereNotIn(): bool
    {
        return Filter::PARAM_NOTIN === $this->operator;
    }

    /**
     * @return bool
     */
    public function isWhereNotNull(): bool
    {
        return Filter::PARAM_NOTNULL === $this->operator;
    }

    /**
     * @return bool
     */
    public function isWhereNull(): bool
    {
        return Filter::PARAM_NULL === $this->operator;
    }
}
