<?php

namespace App\Http\Controllers;

use App\Concerns\Restable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use Restable;

    /**
     * The default limit size.
     *
     * @var int The limit size
     */
    protected int $limit = 20;

    /**
     * The maximum limit size.
     *
     * @var int The limit size
     */
    protected int $maxLimit = 500;

    /**
     * The minimum limit size.
     *
     * @var int The limit size
     */
    protected int $minLimit = 1;

    /**
     * Getter for the limit.
     *
     * @return int The limit size
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Sets and checks the limit.
     *
     * @param  int  $limit The given limit
     */
    public function setLimit(int $limit)
    {
        $this->limit = (int) $this->checkLimit($limit);
    }

    /**
     * Checks the limit.
     *
     * @param * $limit The limit
     * @return int|string The corrected limit
     */
    private function checkLimit($limit): int|string
    {
        // Limit should be numeric
        if (! is_numeric($limit)) {
            return $this->limit;
        }
        // Limit should not be less than the minimum limitation
        if ($limit < $this->minLimit) {
            return $this->minLimit;
        }
        // Limit should not be greater than the maximum limitation
        if ($limit > $this->maxLimit) {
            return $this->maxLimit;
        }
        // If the limit is between the min limit and the max limit, return the limit
        if (! ($limit > $this->maxLimit) && ! ($limit < $this->minLimit)) {
            return $limit;
        }

        // If all fails, return the default limit
        return $this->limit;
    }
}
