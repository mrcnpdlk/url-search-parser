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

class RequestParser
{
    public const SORT_IDENTIFIER   = 'sort';
    public const FILTER_IDENTIFIER = 'filter';

    public const LIMIT_IDENTIFIER  = 'limit';
    public const OFFSET_IDENTIFIER = 'offset';
    public const PAGE_IDENTIFIER   = 'page';
    public const PHRASE_IDENTIFIER = 'phrase';


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
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    public function __construct(string $query)
    {
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
                } elseif (!settype($var, strtolower($type))) {
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
     * @param string $query
     *
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlSearchParser\Exception\InvalidParamException
     */
    private function parse(string $query): void
    {
        parse_str($query, $this->queryParams);

        $this->sort   = new Sort($this->getQueryParam(self::SORT_IDENTIFIER, 'string'));
        $this->filter = new Filter($this->getQueryParam(self::FILTER_IDENTIFIER, 'array', []));
        $this->limit  = $this->getQueryParam('limit', 'int');
        $this->offset = $this->getQueryParam('offset', 'int');
        $this->page   = $this->getQueryParam('page', 'int');
        $this->phrase = $this->getQueryParam('phrase', 'string');
    }
}
