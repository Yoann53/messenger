<?php

declare(strict_types=1);

namespace Kerox\Messenger\Request;

use GuzzleHttp\Psr7\Uri;
use Kerox\Messenger\Helper\UtilityTrait;
use Psr\Http\Message\RequestInterface;

class InsightsRequest extends AbstractRequest implements QueryRequestInterface
{
    use UtilityTrait;

    /**
     * @var array
     */
    protected $metrics;

    /**
     * @var null|int
     */
    protected $since;

    /**
     * @var null|int
     */
    protected $until;

    /**
     * InsightsRequest constructor.
     *
     * @param string   $path
     * @param array    $metrics
     * @param null|int $since
     * @param null|int $until
     */
    public function __construct(string $path, array $metrics, ?int $since = null, ?int $until = null)
    {
        parent::__construct($path);

        $this->metrics = $metrics;
        $this->since = $since;
        $this->until = $until;
    }

    /**
     * @param string $method
     *
     * @return RequestInterface
     */
    public function build(string $method = 'post'): RequestInterface
    {
        $uri = Uri::fromParts([
            'path' => $this->origin->getUri()->getPath(),
            'query' => $this->buildQuery(),
        ]);

        return $this->origin->withUri($uri);
    }

    /**
     * @return string
     */
    public function buildQuery(): string
    {
        $query = [
            'metric' => implode(',', $this->metrics),
            'since' => $this->since,
            'until' => $this->until,
        ];

        return http_build_query($this->arrayFilter($query));
    }
}
