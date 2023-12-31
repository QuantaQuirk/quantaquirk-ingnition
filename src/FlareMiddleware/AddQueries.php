<?php

namespace QuantaQuirk\QuantaQuirkIgnition\FlareMiddleware;

use QuantaQuirk\FlareClient\Report;
use QuantaQuirk\QuantaQuirkIgnition\Recorders\QueryRecorder\QueryRecorder;

class AddQueries
{
    protected QueryRecorder $queryRecorder;

    public function __construct()
    {
        $this->queryRecorder = app(QueryRecorder::class);
    }

    public function handle(Report $report, $next)
    {
        $report->group('queries', $this->queryRecorder->getQueries());

        return $next($report);
    }
}
