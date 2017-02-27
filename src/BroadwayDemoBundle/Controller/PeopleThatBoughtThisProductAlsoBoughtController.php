<?php

/*
 * This file is part of the broadway/broadway-demo package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BroadwayDemoBundle\Controller;

use Broadway\ReadModel\Repository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PeopleThatBoughtThisProductAlsoBoughtController
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $productId
     */
    public function getAdviceAction(Request $request, $productId)
    {
        $readModel = $this->repository->find($productId);

        if (null === $readModel) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($readModel->serialize());
    }
}
