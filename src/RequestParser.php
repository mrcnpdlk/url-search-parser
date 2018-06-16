<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 13:45
 */
declare(strict_types=1);

namespace mrcnpdlk\Lib\UrlQueryParser;


use mrcnpdlk\Lib\UrlQueryParser\Criteria\Filter;
use mrcnpdlk\Lib\UrlQueryParser\Criteria\Sort;

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
     * @var \mrcnpdlk\Lib\UrlQueryParser\Criteria\Sort
     */
    private $sort;
    /**
     * @var \mrcnpdlk\Lib\UrlQueryParser\Criteria\Filter
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
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    public function __construct(string $query)
    {
        $this->parse($query);
    }

    /**
     * @return \mrcnpdlk\Lib\UrlQueryParser\Criteria\Filter
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
     * @return \mrcnpdlk\Lib\UrlQueryParser\Criteria\Sort
     */
    public function getSort(): Sort
    {
        return $this->sort;
    }

    /**
     * @param string $query
     *
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\EmptyParamException
     * @throws \mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException
     */
    private function parse(string $query): void
    {
        parse_str($query, $this->queryParams);

        $this->sort   = new Sort($this->queryParams[self::SORT_IDENTIFIER] ?? null);
        $this->filter = new Filter($this->queryParams[self::FILTER_IDENTIFIER] ?? []);
        $this->limit  = isset($this->queryParams[self::LIMIT_IDENTIFIER]) ? (int)$this->queryParams[self::LIMIT_IDENTIFIER] : null;
        $this->offset = isset($this->queryParams[self::OFFSET_IDENTIFIER]) ? (int)$this->queryParams[self::OFFSET_IDENTIFIER] : null;
        $this->page   = isset($this->queryParams[self::PAGE_IDENTIFIER]) ? (int)$this->queryParams[self::PAGE_IDENTIFIER] : null;
        $this->phrase = isset($this->queryParams[self::PHRASE_IDENTIFIER]) ? (int)$this->queryParams[self::PHRASE_IDENTIFIER] : null;
    }
}
