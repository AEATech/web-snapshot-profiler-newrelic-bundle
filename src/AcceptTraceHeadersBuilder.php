<?php
declare(strict_types=1);

namespace AEATech\WebSnapshotProfilerNewrelicBundle;

use AEATech\SnapshotProfilerNewrelic\Adapter;
use Symfony\Component\HttpFoundation\Request;

class AcceptTraceHeadersBuilder
{
    public const DEFAULT_ACCEPT_HEADERS = [
        'traceparent',
        'tracestate',
        'newrelic',
    ];

    /**
     * @param Request $request
     * @return array
     */
    public function build(Request $request): array
    {
        $result = [];

        $headers = $this->getHeaders($request);
        if (false === empty($headers)) {
            $result = [
                Adapter::ACCEPT_TRACE_TRANSPORT_TYPE => strtoupper($request->getScheme()),
                Adapter::ACCEPT_TRACE_INBOUND_HEADERS => $headers,
            ];
        }

        return $result;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getHeaders(Request $request): array
    {
        $result = [];

        foreach (self::DEFAULT_ACCEPT_HEADERS as $header) {
            $value = $request->headers->get($header);
            if (null !== $value) {
                $result[$header] = $value;
            }
        }

        return $result;
    }
}