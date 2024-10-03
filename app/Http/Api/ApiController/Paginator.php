<?php

namespace App\Http\Api\ApiController;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{
    protected int|null $nrPage = 1;
    protected int|null $nrSize = 5;

    protected $chunk;


    /**
     * @param mixed $collection
     */
    public function __construct(mixed $collection)
    {

        parse_str(request()->getQueryString(),$queryString);
        if(count($queryString) > 0 ){
            $this->nrPage = array_key_exists('page',$queryString) ? $queryString['page'] : 0;
            $this->nrSize = array_key_exists('size',$queryString) ? $queryString['size'] : 5;
        }

        parent::__construct($collection,$collection->count(),$this->nrSize,$this->nrPage);

        $this->chunk = $this->items->chunk($this->nrSize);
    }

    public function response(){


        return new JsonResponse(["data" =>[
            'page' => $this->currentPage(),
            'totalPage' => ($this->chunk->count()-1),
            'size' => $this->perPage(),
            'totalElement' => $this->total(),
            'content' => $this->chunk->has(($this->currentPage()-1)) ? $this->chunk->get(($this->currentPage()-1))->values() : []
        ]
        ],200);
    }
}
