<?php
namespace App\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Log\Logger;

class LoggingErrorListener
{
    /**
     * Log message string with placeholders
     */
    public const LOG_STRING = '{status} [{method}] {uri}: {error}';

    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Exception $error
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __invoke(
        $error,
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        $this->logger->err(self::LOG_STRING, [
            'status' => $response->getStatusCode(),
            'method' => $request->getMethod(),
            'uri'    => (string) $request->getUri(),
            'error'  => $error->getMessage(),
            'trace'  => $error->getTraceAsString(),
        ]);

    }
}
