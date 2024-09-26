<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Service\QueryBuilderServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(PaginatorInterface           $paginator,
                             QueryBuilderServiceInterface $queryBuilderService,
                             Request                      $request): Response
    {
        $queryBuilder = $queryBuilderService->createQueryBuilder('u');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('user/users-list.html.twig', [
            'pagination' => $pagination
        ]);
    }
}