<?php
declare(strict_types=1);

namespace AEATech\WebSnapshotProfilerNewrelicBundle;

use AEATech\SnapshotProfilerNewrelic\Adapter;
use AEATech\WebSnapshotProfilerEventSubscriber\OptionsFactoryInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

class OptionsFactory implements OptionsFactoryInterface
{
    private AcceptTraceHeadersBuilder $acceptedHeadersBuilder;

    /**
     * @param AcceptTraceHeadersBuilder $acceptedHeadersBuilder
     */
    public function __construct(AcceptTraceHeadersBuilder $acceptedHeadersBuilder)
    {
        $this->acceptedHeadersBuilder = $acceptedHeadersBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function factory(KernelEvent $event): array
    {
        $request = $event->getRequest();

        $snapshotName = $request->attributes->get('_route');
        $traceHeaders = $this->acceptedHeadersBuilder->build($request);

        return [
            Adapter::OPTION_KEY_SNAPSHOT_NAME => $snapshotName,
            Adapter::OPTION_KEY_IS_BACKGROUND_PROCESS => false,
            Adapter::OPTION_KEY_ACCEPT_TRACE_HEADERS => $traceHeaders,
        ];
    }
}
