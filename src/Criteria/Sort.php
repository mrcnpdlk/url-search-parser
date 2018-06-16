<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 13:58
 */

namespace mrcnpdlk\Lib\UrlQueryParser\Criteria;


use mrcnpdlk\Lib\UrlQueryParser\Exception;
use mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException;
use Traversable;

class Sort implements \IteratorAggregate
{
    public const DIRECTION_ASC   = 'ASC';
    public const DIRECTION_DESC  = 'DESC';
    public const DELIMITER       = ',';
    public const DESC_IDENTIFIER = '-';

    /**
     * @var \mrcnpdlk\Lib\UrlQueryParser\Criteria\SortParam[]
     */
    private $params = [];

    /**
     * Sort constructor.
     *
     * @param string|null $sortString
     *
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     */
    public function __construct(string $sortString = null)
    {
        /**
         * @var string[] $tParams
         * @var string   $param
         */
        $tParams = $sortString ? explode(self::DELIMITER, $sortString) : [];
        foreach ($tParams as $param) {
            if (empty($param)) {
                throw new EmptyParamException(sprintf('Empty SORT param'));
            }
            if ($param[0] === self::DESC_IDENTIFIER) {
                $param = substr($param, 1);
                if (empty($param)) {
                    throw new EmptyParamException(sprintf('Empty SORT param'));
                }
                $this->params[] = new SortParam($param, self::DIRECTION_DESC);
            } else {
                if (empty($param)) {
                    throw new EmptyParamException(sprintf('Empty SORT param'));
                }
                $this->params[] = new SortParam($param, self::DIRECTION_ASC);
            }
        }
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function __get($key): string
    {
        return $this->params[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key): bool
    {
        return isset($this->params[$key]);
    }

    /**
     * @param string $key
     * @param string $val
     *
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception
     */
    public function __set($key, $val)
    {
        throw new Exception('Direct set is not allowed');
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
        return new \ArrayIterator($this->params);
    }

    /**
     * @return \mrcnpdlk\Lib\UrlQueryParser\Criteria\SortParam[]
     */
    public function toArray(): array
    {
        return $this->params;
    }

}
