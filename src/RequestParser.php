<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 13:45
 */
declare(strict_types=1);

namespace Mrcnpdlk\Lib\UrlSearchParser;

use function in_array;
use InvalidArgumentException;
use function is_array;
use function is_string;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort;
use Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;
use RuntimeException;

class RequestParser
{
    public const SORT_IDENTIFIER   = 'sort';
    public const FILTER_IDENTIFIER = 'filter';
    public const LIMIT_IDENTIFIER  = 'limit';
    public const OFFSET_IDENTIFIER = 'offset';
    public const PAGE_IDENTIFIER   = 'page';
    public const PHRASE_IDENTIFIER = 'phrase';
    /**
     * @var \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter
     */
    private $filter;
    /**
     * @var int|null
     */
    private $limit;
    /**
     * @var int|null
     */
    private $offset;
    /**
     * @var int|null
     */
    private $page;
    /**
     * @var string|null
     */
    private $phrase;
    /**
     * @var array
     */
    private $queryParams = [];
    /**
     * @var \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort
     */
    private $sort;

    /**
     * RequestParser constructor.
     *
     * @param string $query
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function __construct(string $query)
    {
        $this->parse($query);
    }

    public function __toString(): string
    {
        return $this->getQuery();
    }

    /**
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }

    /**
     * @param \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter $filter
     *
     * @return $this
     */
    public function setFilter(Filter $filter): self
    {
        $this->filter = $filter;

        $tRes = [];
        foreach ($filter->toArray() as $item) {
            if (!array_key_exists($item->param, $tRes)) {
                $tRes[$item->param] = [];
            }
            $tRes[$item->param][$item->operator] = $item->value;
        }

        if (0 === count($tRes)) {
            unset($this->queryParams[self::FILTER_IDENTIFIER]);
        } else {
            $this->queryParams[self::FILTER_IDENTIFIER] = $tRes;
        }

        return $this;
    }

    /**
     * @param int|null $default
     *
     * @return int|null
     */
    public function getLimit(int $default = null): ?int
    {
        return $this->limit ?? $default;
    }

    /**
     * @param int|null $limit
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     *
     * @return $this
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        if (null !== $this->limit && $this->limit < 0) {
            throw new InvalidParamException('Limit value cannot be lower than 0');
        }
        if (null === $limit) {
            unset($this->queryParams[self::LIMIT_IDENTIFIER]);
        } else {
            $this->queryParams[self::LIMIT_IDENTIFIER] = $limit;
        }

        return $this;
    }

    /**
     * @param int|null $default
     *
     * @return int|null
     */
    public function getOffset(int $default = null): ?int
    {
        return $this->offset ?? $default;
    }

    /**
     * @param int|null $offset
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     *
     * @return $this
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        if (null !== $this->offset && $this->offset < 0) {
            throw new InvalidParamException('Offset value cannot be lower than 0');
        }
        if (null === $offset) {
            unset($this->queryParams[self::OFFSET_IDENTIFIER]);
        } else {
            $this->queryParams[self::OFFSET_IDENTIFIER] = $offset;
        }

        return $this;
    }

    /**
     * @param int|null $default
     *
     * @return int|null
     */
    public function getPage(int $default = null): ?int
    {
        return $this->page ?? $default;
    }

    /**
     * @param int|null $page
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     *
     * @return $this
     */
    public function setPage(?int $page): self
    {
        $this->page = $page;
        if (null !== $this->page && $this->page < 0) {
            throw new InvalidParamException('Page value cannot be lower than 0');
        }
        if (null === $page) {
            unset($this->queryParams[self::PAGE_IDENTIFIER]);
        } else {
            $this->queryParams[self::PAGE_IDENTIFIER] = $page;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhrase(): ?string
    {
        return $this->phrase;
    }

    /**
     * @param string|null $phrase
     *
     * @return $this
     */
    public function setPhrase(?string $phrase): self
    {
        $this->phrase = $phrase;
        if (null === $phrase) {
            unset($this->queryParams[self::PHRASE_IDENTIFIER]);
        } else {
            $this->queryParams[self::PHRASE_IDENTIFIER] = $phrase;
        }

        return $this;
    }

    /**
     * @param string|null $arg_separator
     * @param int         $enc_type
     *
     * @return string
     */
    public function getQuery(string $arg_separator = null, int $enc_type = \PHP_QUERY_RFC1738): string
    {
        return http_build_query($this->queryParams, null ?? '', $arg_separator ?? (ini_get('arg_separator.output') ?: '&'), $enc_type);
    }

    /**
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception
     *
     * @return string
     */
    public function getQueryHash(): string
    {
        try {
            $resJson = json_encode($this->queryParams);
            if (false === $resJson) {
                throw new RuntimeException('Cannot generate json_encode');
            }
            /** @var string|false $res */
            $res = md5($resJson);
            if (false === $res) {
                throw new RuntimeException('Cannot generate md5 hash');
            }
        } catch (RuntimeException $e) {
            throw new Exception(sprintf('Cannot query hash. %s', $e->getMessage()));
        }

        return $res;
    }

    /**
     * @param string      $param
     * @param string|null $type    If NULL return value AS IS
     * @param mixed|null  $default
     *
     * @return mixed|null
     */
    public function getQueryParam(string $param, string $type = null, $default = null)
    {
        if (isset($this->queryParams[$param])) {
            if (null !== $type) {
                $type = strtolower($type);
                if (!in_array($type, ['boolean', 'bool', 'integer', 'int', 'float', 'double', 'string', 'array'])) {
                    throw new InvalidArgumentException(sprintf('Unsupported type [%s]', $type));
                }

                $var = $this->queryParams[$param];

                if ('array' === $type && is_string($var)) {
                    $var = explode(',', $var);
                } elseif ('string' === $type && is_array($var)) {
                    $var = implode(',', $var);
                } elseif (in_array($type, ['boolean', 'bool']) && in_array(strtolower($var), ['true', 'false'])) {
                    $var = ('true' === strtolower($var));
                } elseif (!settype($var, $type)) {
                    throw new RuntimeException(sprintf('Cannot set type [%s]', $type));
                }

                return $var;
            }

            return $this->queryParams[$param];
        }

        return $default ?? null;
    }

    /**
     * @return \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort
     */
    public function getSort(): Sort
    {
        return $this->sort;
    }

    /**
     * @param \Mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort $sort
     *
     * @return $this
     */
    public function setSort(Sort $sort): self
    {
        $this->sort = $sort;

        $tRes = [];
        foreach ($sort->toArray() as $param) {
            $tRes[] = Sort::DIRECTION_DESC === $param->direction ? Sort::DESC_IDENTIFIER . $param->param : $param->param;
        }
        if (0 === count($tRes)) {
            unset($this->queryParams[self::SORT_IDENTIFIER]);
        } else {
            $this->queryParams[self::SORT_IDENTIFIER] = implode(Sort::DELIMITER, $tRes);
        }

        return $this;
    }

    /**
     * @param string $param
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     *
     * @return $this
     */
    public function removeQueryParam(string $param): self
    {
        if (in_array($param, [
            self::FILTER_IDENTIFIER,
            self::SORT_IDENTIFIER,
            self::PHRASE_IDENTIFIER,
            self::OFFSET_IDENTIFIER,
            self::LIMIT_IDENTIFIER,
            self::PAGE_IDENTIFIER,
        ], true)) {
            throw new InvalidParamException(sprintf('Cannot remove %s param. Use `set<param name>` with empty arg', $param));
        }
        unset($this->queryParams[$param]);

        return $this;
    }

    /**
     * @param string $param
     * @param        $value
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     *
     * @return $this
     */
    public function setQueryParam(string $param, $value): self
    {
        if (in_array($param, [
            self::FILTER_IDENTIFIER,
            self::SORT_IDENTIFIER,
            self::PHRASE_IDENTIFIER,
            self::OFFSET_IDENTIFIER,
            self::LIMIT_IDENTIFIER,
            self::PAGE_IDENTIFIER,
        ], true)) {
            throw new InvalidParamException(sprintf('Cannot set %s param. Use `set<param name>` with empty arg', $param));
        }
        $this->queryParams[$param] = $value;

        return $this;
    }

    /**
     * @param string $query
     *
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\DuplicateParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \Mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    private function parse(string $query): void
    {
        parse_str($query, $this->queryParams);

        $this->setSort(new Sort($this->getQueryParam(self::SORT_IDENTIFIER, 'string')));
        $this->setFilter(new Filter($this->getQueryParam(self::FILTER_IDENTIFIER, 'array', [])));
        $this->setLimit($this->getQueryParam(self::LIMIT_IDENTIFIER, 'int'));
        $this->setOffset($this->getQueryParam(self::OFFSET_IDENTIFIER, 'int'));
        $this->setPage($this->getQueryParam(self::PAGE_IDENTIFIER, 'int'));
        $this->setPhrase($this->getQueryParam(self::PHRASE_IDENTIFIER, 'string'));
    }
}
