<?php

/**
 * Copyright (c) 2010-2017 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Middleware\Twig;

use Eureka\Component\Config\Config;
use Eureka\Component\Psr\Http\Middleware\DelegateInterface;
use Eureka\Component\Psr\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Message;

class TwigMiddleware implements ServerMiddlewareInterface
{
    /**
     * @var Config config
     */
    protected $config = null;

    /**
     * TwigMiddleware constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Message\ServerRequestInterface  $request
     * @param DelegateInterface $frame
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(Message\ServerRequestInterface $request, DelegateInterface $frame)
    {
        //~ Add twig loader to the request.
        $request = $request->withAttribute('twigLoader', $this->getTwigLoader());

        return $frame->next($request);
    }

    /**
     * Run application middleware.
     *
     * @param  Message\ServerRequestInterface $request
     * @return Message\ResponseInterface
     */
    private function getTwigLoader()
    {
        $twigLoader = new \Twig_Loader_Filesystem([]);
        $twigLoader->addPath($this->config->get('global.theme.path.template'), 'template');

        return $twigLoader;
    }
}