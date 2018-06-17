<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 19:05
 */
declare(strict_types=1);

namespace mrcnpdlk\Lib\UrlSearchParser\Criteria;


use mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;
use Traversable;

/**
 * Class Filter
 *
 * @package mrcnpdlk\Lib\UrlSearchParser\Criteria
 */
class Filter implements \IteratorAggregate
{
    public const DELIMITER = ',';

    public const PARAM_EQ    = 'eq';
    public const PARAM_GT    = 'gt';
    public const PARAM_GTE   = 'gte';
    public const PARAM_LT    = 'lt';
    public const PARAM_LTE   = 'lte';
    public const PARAM_LIKE  = 'like';
    public const PARAM_IN    = 'in';
    public const PARAM_NOTIN = 'notin';

    public static $allowedOperators = [
        self::PARAM_EQ    => '=',
        self::PARAM_GT    => '>',
        self::PARAM_GTE   => '>=',
        self::PARAM_LT    => '<',
        self::PARAM_LTE   => '<=',
        self::PARAM_LIKE  => null,
        self::PARAM_IN    => null,
        self::PARAM_NOTIN => null,
    ];

    /**
     * @var \mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam[]
     */
    private $filters = [];

    /**
     * Filter constructor.
     *
     * @param mixed $filterArray
     *
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     * @todo Check PLUS sign in sitring %2B code
     */
    public function __construct($filterArray = [])
    {
        if (!\is_array($filterArray)) {
            throw new InvalidParamException(sprintf('FILTER params are invalid. Is [%s], Array expected', \gettype($filterArray)));
        }

        foreach ($filterArray as $param => $filters) {
            if(!\is_string($param)){
                throw new InvalidParamException(sprintf('Key in FILTER param is not a string'));
            }
            if (\is_array($filters)) {
                foreach ($filters as $operator => $value) {
                    if (!\array_key_exists($operator, self::$allowedOperators)) {
                        throw new InvalidParamException(sprintf('Operator [%s] in FILTER is not allowed', $operator));
                    }
                    if (\in_array($operator, [self::PARAM_IN, self::PARAM_NOTIN], true)) {
                        $value = explode(self::DELIMITER, $value);
                    }
                    $this->filters[] = new FilterParam($param, $operator, $value);
                }
            } elseif (\is_string($filters)) {
                $this->filters[] = new FilterParam($param, self::PARAM_EQ, $filters);
            }
        }
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->filters);
    }

    /**
     * @return \mrcnpdlk\Lib\UrlSearchParser\Criteria\FilterParam[]
     */
    public function toArray(): array
    {
        return $this->filters;
    }
}
