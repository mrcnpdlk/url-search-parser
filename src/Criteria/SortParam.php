<?php
/**
 * Created by Marcin.
 * Date: 16.06.2018
 * Time: 19:21
 */
declare(strict_types=1);

namespace mrcnpdlk\Lib\UrlQueryParser\Criteria;


class SortParam
{
    /**
     * @var string
     */
    public $param;
    /**
     * @var string
     */
    public $direction;

    /**
     * SortParam constructor.
     *
     * @param string $param
     * @param string $direction
     */
    public function __construct(string $param, string $direction)
    {
        $this->param     = $param;
        $this->direction = $direction;
    }
}
