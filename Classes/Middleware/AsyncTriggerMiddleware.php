<?php

namespace Visol\Ipauthtrigger\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Visol\Ipauthtrigger\Service\AuthenticationService;

class AsyncTriggerMiddleware implements MiddlewareInterface
{

    protected ResponseFactoryInterface $responseFactory;

    public function injectResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUri()->getPath() !== '/tx-ipauthtrigger-async.html') {
            return $handler->handle($request);
        }

        $response = $this->responseFactory->createResponse()->withHeader(
            'Content-Type',
            'application/javascript; charset=UTF-8'
        )->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')->withAddedHeader(
            'Cache-Control',
            'post-check=0, pre-check=0'
        )->withHeader('Pragma', 'no-cache');

        $frontendUser = $request->getAttribute('frontend.user');

        if ($frontendUser->user) {
            // Don't do anything if a user is already authenticated
            return $response;
        }

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $objectManager->get(AuthenticationService::class);

        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $users = $authenticationService->findAllUsersByIpAuthentication($ip);

        if (count($users) === 0) {
            // No IP user, exit
            return $response;
        }

        // Serve a JavaScript that reloads the current page with ?logintype=login
        $response->getBody()->write(
            "
url = location.href;
url += (url.split('?')[1] ? '&':'?') + 'logintype=login';
window.location.replace(url);
            "
        );
        return $response;
    }
}
