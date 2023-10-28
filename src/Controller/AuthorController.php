<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AddAuthorType;
use App\Repository\AuthorRepository;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    /**
     * @Route("/author/{name}", name="author_show")
     */
    public function showAuthor($name): Response
    {
        return $this->render('author/showAuthor.html.twig', [
            'name' => $name,
        ]);
    }


    /**
     * @Route("/authorlist", name="list_author")
     */
    public function list()
    {
        $authors = array(

            array('id' => 1, 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
            array('id' => 2, 'username' => 'William Shakespeare', 'email' =>  'william.shakespeare@gmail.com', 'nb_books' => 200),
            array('id' => 3, 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );

        return $this->render(
            "author/list.html.twig",
            array('tabAuthors' => $authors)
        );
    }

    /**
     * @Route("/authorlist2", name="list_author2")
     */
    public function listAuthor(AuthorRepository $repository)
    {
        $authors = $repository->findAll();

        return $this->render(
            "author/authors.html.twig",
            array('tabAuthors' => $authors)
        );
    }

    #[Route('/addauthor', name: 'add_author')]
    public function addAuthor(ManagerRegistry $managerRegistry)
    {
        $author = new Author();
        $author->setName("Arij");
        $author->setMail("arij@gmail.com");

        #1ere method
        #$em= $this->getDoctrine()->getManager();

        #2methode
        $em = $managerRegistry->getManager();

        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute("list_author2");
    }

    #[Route('/update/{id}', name: 'update_author')]
    public function update($id, AuthorRepository $repository, ManagerRegistry $managerRegistry)
    {
        $author = $repository->find($id);

        $author->setName("Ali");
        $author->setMail("ali@gmail.com");

        $em = $managerRegistry->getManager();

        $em->flush();

        return $this->redirectToRoute("list_author2");
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deleteAuthor($id, AuthorRepository $repository, ManagerRegistry $managerRegistry)
    {
        $author = $repository->find($id);

        $em = $managerRegistry->getManager();

        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute("list_author2");
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, ManagerRegistry $managerRegistry)
    {
        $author = new Author();

        $form = $this->createForm(AddAuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $managerRegistry->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("list_author2");
        }

        //     1ère methode
        /*        return $this->render("author/add.html.twig",
                    array('authorForm'=>$form->createView()));*/


        //        2ème méthode
        return $this->renderForm(
            "author/add.html.twig",
            array('authorForm' => $form)
        );
    }

    #[Route('/listauthorsbyemail', name: 'list_authors_by_email')]
    public function listAuthorByEmail(AuthorRepository $repository)
    {
        $authors = $repository->findAll();
        $authorsByEmail = $repository->listAuthorByEmail();
        return $this->render(
            "author/authorsbyemail.html.twig",
            array(
                'tabAuthors' => $authors,
                'tabauthorsByEmail' => $authorsByEmail,
            )
        );
    }
}
