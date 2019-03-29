<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 13:58
 */

namespace Mrcnpdlk\Lib\UrlSearchParser\Criteria;

use ArrayIterator;
use IteratorAggregate;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException;
use Traversable;

/**
 * Class Sort
 */
class Sort implements IteratorAggregate
{
    public const DIRECTION_ASC   = 'ASC';
    public const DIRECTION_DESC  = 'DESC';
    public const DELIMITER       = ',';
    public const DESC_IDENTIFIER = '-';

    /**
     * @var \Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam[]
     */
    private $params = [];

    /**
     * Sort constructor.
     *
     * @param string|null $sortString
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     */
    public function __construct(string $sortString = null)
    {
        /**
         * @var string[]
         * @var string   $param
         */
        $tParams = $sortString ? explode(self::DELIMITER, $sortString) : [];
        foreach ($tParams as $param) {
            if (empty($param)) {
                throw new EmptyParamException(sprintf('Empty SORT param'));
            }
            if (self::DESC_IDENTIFIER === $param[0]) {
                $param = substr($param, 1);
                if (empty($param)) {
                    throw new EmptyParamException(sprintf('Empty SORT param'));
                }
                $this->params[] = new SortParam($param, self::DIRECTION_DESC);
            } else {
                $this->params[] = new SortParam($param, self::DIRECTION_ASC);
            }
        }
    }

    /**
     * Retrieve an external iterator
     *
     * @see  http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *                     <b>Traversable</b>
     *
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->params);
    }

    /**
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam[]
     */
    public function toArray(): array
    {
        return $this->params;
    }
}
