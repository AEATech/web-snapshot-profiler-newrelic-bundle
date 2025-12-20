<?php
declare(strict_types=1);

namespace AEATech\Tests;

use AEATech\SnapshotProfilerNewrelic\Adapter;
use AEATech\WebSnapshotProfilerNewrelicBundle\AcceptTraceHeadersBuilder;
use AEATech\WebSnapshotProfilerNewrelicBundle\OptionsFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class OptionsFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const ROUTE_NAME = 'route_name';

    private const TRACE_HEADERS = ['trace headers'];

    private const PROFILING_OPTIONS = [
        Adapter::OPTION_KEY_SNAPSHOT_NAME => self::ROUTE_NAME,
        Adapter::OPTION_KEY_IS_BACKGROUND_PROCESS => false,
        Adapter::OPTION_KEY_ACCEPT_TRACE_HEADERS => self::TRACE_HEADERS,
    ];

    private OptionsFactory $optionsFactory;

    private AcceptTraceHeadersBuilder&MockInterface $acceptTraceHeadersBuilder;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->acceptTraceHeadersBuilder = Mockery::mock(AcceptTraceHeadersBuilder::class);

        $this->optionsFactory = new OptionsFactory($this->acceptTraceHeadersBuilder);
    }

    /**
     * @return void
     */
    #[Test]
    public function factory(): void
    {
        $request = new Request([], [], ['_route' => self::ROUTE_NAME]);

        $this->acceptTraceHeadersBuilder->shouldReceive('build')
            ->once()
            ->with($request)
            ->andReturn(self::TRACE_HEADERS);

        $event = Mockery::mock(KernelEvent::class);
        $event->shouldReceive('getRequest')
            ->once()
            ->withNoArgs()
            ->andReturn($request);

        $actual = $this->optionsFactory->factory($event);

        self::assertSame(self::PROFILING_OPTIONS, $actual);
    }
}
