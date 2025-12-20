<?php
declare(strict_types=1);

namespace AEATech\Tests;

use AEATech\SnapshotProfilerNewrelic\Adapter;
use AEATech\WebSnapshotProfilerNewrelicBundle\AcceptTraceHeadersBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AcceptTraceHeadersBuilderTest extends TestCase
{
    private AcceptTraceHeadersBuilder $acceptTraceHeadersBuilder;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->acceptTraceHeadersBuilder = new AcceptTraceHeadersBuilder();
    }

    /**
     * @return array
     */
    public static function buildDataProvider(): array
    {
        return [
            'http scheme' => [
                'headers' => [
                    'traceparent' => 'value1',
                    'tracestate' => 'value2',
                    'newrelic' => 'value3',
                ],
                'isHttps' => false,
                'expected' => [
                    Adapter::ACCEPT_TRACE_TRANSPORT_TYPE => 'HTTP',
                    Adapter::ACCEPT_TRACE_INBOUND_HEADERS => [
                        'traceparent' => 'value1',
                        'tracestate' => 'value2',
                        'newrelic' => 'value3',
                    ],
                ],
            ],
            'https scheme' => [
                'headers' => [
                    'traceparent' => 'value1',
                    'tracestate' => 'value2',
                    'newrelic' => 'value3',
                ],
                'isHttps' => true,
                'expected' => [
                    Adapter::ACCEPT_TRACE_TRANSPORT_TYPE => 'HTTPS',
                    Adapter::ACCEPT_TRACE_INBOUND_HEADERS => [
                        'traceparent' => 'value1',
                        'tracestate' => 'value2',
                        'newrelic' => 'value3',
                    ],
                ],
            ],
            'not found headers to accept' => [
                'headers' => [
                    'custom_header1' => 'value1',
                    'custom_header2' => 'value2',
                ],
                'isHttps' => false,
                'expected' => [],
            ],
            'empty headers' => [
                'headers' => [],
                'isHttps' => false,
                'expected' => [],
            ],
        ];
    }

    /**
     * @param array $headers
     * @param bool $isHttps
     * @param array $expected
     *
     * @return void
     */
    #[Test]
    #[DataProvider('buildDataProvider')]
    public function build(array $headers, bool $isHttps, array $expected): void
    {
        $request = new Request();
        $request->server->set('HTTPS', $isHttps);

        foreach ($headers as $key => $value) {
            $request->headers->set($key, $value);
        }

        $actual = $this->acceptTraceHeadersBuilder->build($request);

        self::assertSame($expected, $actual);
    }
}