<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AddBookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/authorlist3", name="list_author3")
     */
    public function listAuthor(BookRepository $repository)
    {
        $books = $repository->findAll();

        return $this->render(
            "book/books.html.twig",
            array('tabBooks' => $books)
        );
    }

    #[Route('/addbook', name: 'addbook')]
    public function addbook(Request $request, ManagerRegistry $managerRegistry)
    {
        $book = new Book();

        $form = $this->createForm(AddBookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $managerRegistry->getManager();

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute("list_author3");
        }

        return $this->renderForm(
            "book/add.html.twig",
            array('bookForm' => $form)
        );
    }
}
