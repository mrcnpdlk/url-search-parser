<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 19:05
 */
declare(strict_types=1);

namespace Mrcnpdlk\Lib\UrlSearchParser\Criteria;

use function array_key_exists;
use ArrayIterator;
use function gettype;
use function in_array;
use function is_array;
use function is_string;
use IteratorAggregate;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;
use Traversable;

/**
 * Class Filter
 */
class Filter implements IteratorAggregate
{
    public const DELIMITER = ',';

    public const PARAM_EQ      = 'eq';
    public const PARAM_GT      = 'gt';
    public const PARAM_GTE     = 'gte';
    public const PARAM_LT      = 'lt';
    public const PARAM_LTE     = 'lte';
    public const PARAM_LIKE    = 'like';
    public const PARAM_IN      = 'in';
    public const PARAM_NOTIN   = 'notin';
    public const PARAM_NULL    = 'null';
    public const PARAM_NOTNULL = 'notnull';
    public const PARAM_NOT     = 'not';

    /**
     * @var array<string,string|null>
     */
    public static $allowedOperators = [
        self::PARAM_EQ      => '=',
        self::PARAM_GT      => '>',
        self::PARAM_GTE     => '>=',
        self::PARAM_LT      => '<',
        self::PARAM_LTE     => '<=',
        self::PARAM_LIKE    => 'like',
        self::PARAM_IN      => null,
        self::PARAM_NOTIN   => null,
        self::PARAM_NULL    => null,
        self::PARAM_NOTNULL => null,
        self::PARAM_NOT     => '!=',
    ];

    /**
     * @var \Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam[]
     */
    private $filters = [];

    /**
     * Filter constructor.
     *
     * @param array|FilterParam[] $filterArray
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     *
     * @todo Check PLUS sign in string %2B code
     */
    public function __construct($filterArray = [])
    {
        if (!is_array($filterArray)) {
            throw new InvalidParamException(sprintf('FILTER params are invalid. Is [%s], Array expected', gettype($filterArray)));
        }

        /** @var FilterParam|string|array<string,string> $filters */
        foreach ($filterArray as $param => $filters) {
            if ($filters instanceof FilterParam) {
                $this->appendParam($filters);
                continue;
            }

            if (!is_string($param)) {
                throw new InvalidParamException(sprintf('Key in FILTER param is not a string'));
            }
            if (is_array($filters)) {
                /** @var string $operator */
                foreach ($filters as $operator => $value) {
                    if (!array_key_exists($operator, self::$allowedOperators)) {
                        throw new InvalidParamException(sprintf('Operator [%s] in FILTER is not allowed', $operator));
                    }
                    if (in_array($operator, [self::PARAM_IN, self::PARAM_NOTIN], true)) {
                        $value = '' === $value ? [] : explode(self::DELIMITER, $value);
                    }
                    if (in_array($operator, [self::PARAM_NULL, self::PARAM_NOTNULL], true)) {
                        $value = null;
                    }
                    $this->appendParam(new FilterParam($param, $operator, $value));
                }
            } elseif (is_string($filters)) {
                $this->appendParam(new FilterParam($param, self::PARAM_EQ, $filters));
            }
        }
    }

    /**
     * @param \Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam $filterParam
     *
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter<\Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam>
     */
    public function appendParam(FilterParam $filterParam): Filter
    {
        $this->filters[] = $filterParam;

        return $this;
    }

    /**
     * @param string $paramName
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     *
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter<\Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam>
     */
    public function getByParam(string $paramName): Filter
    {
        $params = [];
        foreach ($this as $item) {
            if ($item->param === $paramName) {
                $params[] = $item;
            }
        }

        return new self($params);
    }

    /**
     * Retrieve an external iterator
     *
     * @see   http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable<\Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam> An instance of an object implementing <b>Iterator</b> or
     *                                                                         <b>Traversable</b>
     *
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->filters);
    }

    /**
     * @param \Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam $filterParam
     *
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter
     */
    public function replaceParam(FilterParam $filterParam): Filter
    {
        $isChanged = false;
        foreach ($this->filters as &$filter) {
            if ($filter->param === $filterParam->param && $filter->operator = $filterParam->operator) {
                $filter->value = $filterParam->value;
                $isChanged     = true;
            }
        }
        unset($filter);
        if (!$isChanged) {
            $this->appendParam($filterParam);
        }

        return $this;
    }

    /**
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam[]
     */
    public function toArray(): array
    {
        return $this->filters;
    }
}
