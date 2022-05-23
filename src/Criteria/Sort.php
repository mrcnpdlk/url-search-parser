<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 13:58
 */

namespace Mrcnpdlk\Lib\UrlSearchParser\Criteria;

use ArrayIterator;
use IteratorAggregate;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException;
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
    private $sortParams = [];

    /**
     * Sort constructor.
     *
     * @param string|null $sortString
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function __construct(string $sortString = null)
    {
        /** @var string[] $tParams */
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
                $this->appendParam(new SortParam($param, self::DIRECTION_DESC));
            } else {
                $this->appendParam(new SortParam($param, self::DIRECTION_ASC));
            }
        }
    }

    /**
     * @param \Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam $sortParam
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException
     *
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort
     */
    public function appendParam(SortParam $sortParam): Sort
    {
        if ($this->isExists($sortParam->param)) {
            throw new DuplicateParamException(sprintf('Duplicate Sort param `%s`', $sortParam->param));
        }
        $this->sortParams[] = $sortParam;

        return $this;
    }

    /**
     * Removing parametr by name
     *
     * @param string $paramName
     *
     * @return $this
     */
    public function removeByParamName(string $paramName): Sort
    {
        foreach ($this->sortParams as $key => $param) {
            if ($param->param === $paramName) {
                unset($this->sortParams[$key]);
            }
        }

        return $this;
    }

    /**
     * @param string $paramName
     *
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam[]
     */
    public function getByParamName(string $paramName): array
    {
        $params = [];
        foreach ($this->sortParams as $param) {
            if ($param->param === $paramName) {
                $params[] = $param;
            }
        }

        return $params;
    }

    /**
     * Retrieve an external iterator
     *
     * @see   http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable<\Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam> An instance of an object implementing <b>Iterator</b> or
     *                                                                       <b>Traversable</b>
     *
     * @since 5.0.0
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->sortParams);
    }

    /**
     * @param string $paramName
     *
     * @return bool
     */
    public function isExists(string $paramName): bool
    {
        foreach ($this->sortParams as $param) {
            if ($param->param === $paramName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam $sortParam
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException
     *
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort
     */
    public function replaceParam(SortParam $sortParam): Sort
    {
        $isChanged = false;
        foreach ($this->sortParams as &$param) {
            if ($param->param === $sortParam->param) {
                $param->direction = $sortParam->direction;
                $isChanged        = true;
            }
        }
        unset($param);
        if (!$isChanged) {
            $this->appendParam($sortParam);
        }

        return $this;
    }

    /**
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\SortParam[]
     */
    public function toArray(): array
    {
        return $this->sortParams;
    }
}
