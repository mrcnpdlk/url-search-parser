<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 13:45
 */
declare(strict_types=1);

namespace mrcnpdlk\Lib\UrlSearchParser;


use mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter;
use mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort;
use mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException;
use mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException;

class RequestParser
{
    public const SORT_IDENTIFIER   = 'sort';
    public const FILTER_IDENTIFIER = 'filter';

    public const LIMIT_IDENTIFIER  = 'limit';
    public const OFFSET_IDENTIFIER = 'offset';
    public const PAGE_IDENTIFIER   = 'page';
    public const PHRASE_IDENTIFIER = 'phrase';

    /**
     * @var string
     */
    private $query;
    /**
     * @var array
     */
    private $queryParams = [];
    /**
     * @var \mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort
     */
    private $sort;
    /**
     * @var \mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter
     */
    private $filter;
    /**
     * @var integer|null
     */
    private $limit;
    /**
     * @var integer|null
     */
    private $page;
    /**
     * @var integer|null
     */
    private $offset;
    /**
     * @var string|null
     */
    private $phrase;

    /**
     * RequestParser constructor.
     *
     * @param string $query
     *
     * @throws Exception
     * @throws EmptyParamException
     * @throws InvalidParamException
     */
    public function __construct(string $query)
    {
        $this->query = $query;
        $this->parse($query);
    }

    /**
     * @return \mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
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
     * @param int|null $default
     *
     * @return int|null
     */
    public function getOffset(int $default = null): ?int
    {
        return $this->offset ?? $default;
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
     * @return null|string
     */
    public function getPhrase(): ?string
    {
        return $this->phrase;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getQueryHash(): string
    {
        return md5(json_encode($this->queryParams));
    }

    /**
     * @param string      $param
     * @param string|null $type If NULL return value AS IS
     * @param mixed|null  $default
     *
     * @return mixed|null
     */
    public function getQueryParam(string $param, string $type = null, $default = null)
    {
        if (isset($this->queryParams[$param])) {
            if ($type !== null) {
                $type = strtolower($type);
                if (!\in_array($type, ['boolean', 'bool', 'integer', 'int', 'float', 'double', 'string', 'array'])) {
                    throw new \InvalidArgumentException(sprintf('Unsupported type [%s]', $type));
                }

                $var = $this->queryParams[$param];

                if ($type === 'array' && \is_string($var)) {
                    $var = explode(',', $var);
                } elseif ($type === 'string' && \is_array($var)) {
                    $var = implode(',', $var);
                } elseif (\in_array($type, ['boolean', 'bool']) && \in_array(strtolower($var), ['true', 'false'])) {
                    $var = (strtolower($var) === 'true');
                } elseif (!settype($var, $type)) {
                    throw new \RuntimeException(sprintf('Cannot set type [%s]', $type));
                }

                return $var;
            }

            return $this->queryParams[$param];
        }

        return $default ?? null;
    }

    /**
     * @return \mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort
     */
    public function getSort(): Sort
    {
        return $this->sort;
    }

    /**
     * @param string $param
     *
     * @return $this
     */
    public function removeQueryParam(string $param): self
    {
        unset($this->queryParams[$param]);

        return $this;
    }

    /**
     * @param \mrcnpdlk\Lib\UrlSearchParser\Criteria\Filter $filter
     *
     * @return $this
     */
    public function setFilter(Filter $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param int|null $limit
     *
     * @return $this
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        if (null !== $this->limit && $this->limit < 0) {
            throw new InvalidParamException('Limit value cannot be lower than 0');
        }

        return $this;
    }

    /**
     * @param int|null $offset
     *
     * @return $this
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        if (null !== $this->offset && $this->offset < 0) {
            throw new InvalidParamException('Offset value cannot be lower than 0');
        }

        return $this;
    }

    /**
     * @param int|null $page
     *
     * @return $this
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function setPage(?int $page): self
    {
        $this->page = $page;
        if (null !== $this->page && $this->page < 0) {
            throw new InvalidParamException('Page value cannot be lower than 0');
        }

        return $this;
    }

    /**
     * @param string|null $phrase
     *
     * @return $this
     */
    public function setPhrase(?string $phrase): self
    {
        $this->phrase = $phrase;

        return $this;
    }

    /**
     * @param \mrcnpdlk\Lib\UrlSearchParser\Criteria\Sort $sort
     *
     * @return $this
     */
    public function setSort(Sort $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param string $query
     *
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    private function parse(string $query): void
    {
        parse_str($query, $this->queryParams);

        $this->setSort(new Sort($this->getQueryParam(self::SORT_IDENTIFIER, 'string')));
        $this->setFilter(new Filter($this->getQueryParam(self::FILTER_IDENTIFIER, 'array', [])));
        $this->setLimit($this->getQueryParam('limit', 'int'));
        $this->setOffset($this->getQueryParam('offset', 'int'));
        $this->setPage($this->getQueryParam('page', 'int'));
        $this->setPhrase($this->getQueryParam('phrase', 'string'));
    }

}
