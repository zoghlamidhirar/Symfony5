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

            $book->setPublished('true');
            //$nbrBook = $book->getAuthor()->getNbBooks();

            //$book->getAuthor()->setNbBooks($nbrBook + 1);

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute("list_author3");
        }

        return $this->renderForm(
            "book/add.html.twig",
            array('bookForm' => $form)
        );
    }

    #[Route('/updateBook/{ref}', name: 'update_book')]
    public function updateBook($ref, BookRepository $repository, ManagerRegistry $manager, Request $request): Response
    {
        $book = $repository->find($ref);
        $form = $this->createForm(AddBookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $manager->getManager();
            $em->flush();
            return $this->redirectToRoute('list_author3');
        }
        return $this->renderForm('book/updateBook.html.twig', ['form' => $form]);
    }

    #[Route('/showBook/{ref}', name: 'show_book')]
    public function showAuthor($ref, BookRepository $repository)
    {
        return $this->render(
            'book/show.html.twig',
            array('book' => $repository->find($ref))
        );
    }
}
