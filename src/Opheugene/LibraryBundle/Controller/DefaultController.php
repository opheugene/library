<?php

namespace Opheugene\LibraryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Opheugene\LibraryBundle\Entity\Book;
use Opheugene\LibraryBundle\Form\Type\BookType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository("OpheugeneLibraryBundle:Book");

        //$books = $repository->findBy(array(), array("read" => "ASC"));
        $books = $repository->findAllOrderedByReadDate();

        //echo $this->container->getParameter("document_root");
        return $this->render('OpheugeneLibraryBundle:Default:index.html.twig', array("books" => $books));
    }

    public function newAction(Request $request)
    {
        // create
        $book = new Book();
        $form = $this->createForm(new BookType(), $book);

        // request
        $form->handleRequest($request);

        // check values
        if ($form->isValid()) {

            // upload files
            $book->uploadCover();
            $book->uploadFile();

            // save
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            // clear cache
            $cacheDriver = $em->getConfiguration()->getResultCacheImpl();
            $cacheDriver->delete('book_list');

            // go to index page
            return $this->redirect($this->generateUrl('index'));
        }

        // show form
        return $this->render('OpheugeneLibraryBundle:Default:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function updateAction($id, Request $request)
    {
        // get book by id
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository("OpheugeneLibraryBundle:Book")->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        // create form
        $form = $this->createForm(new BookType(), $book);

        // request
        $form->handleRequest($request);

        // check values
        if ($form->isValid()) {

            //$data = $form->getData();

            // delete book
            if ($form->get("delete")->getData()) {
                $em->remove($book);

            // upload files
            } else {

                // cover
                if ($book->getCover() && $form->get("delete_cover")->getData()) {
                    $book->deleteCover();
                }
                $book->uploadCover();

                // file
                if ($book->getFile() && $form->get("delete_file")->getData()) {
                    $book->deleteFile();
                }
                $book->uploadFile();
            }

            // save
            $em->flush();

            // clear cache
            $cacheDriver = $em->getConfiguration()->getResultCacheImpl();
            $cacheDriver->delete('book_list');

            // go to index page
            return $this->redirect($this->generateUrl('index'));
        }

        return $this->render('OpheugeneLibraryBundle:Default:update.html.twig', array(
            'form' => $form->createView(),
            'book' => $book
        ));
    }

    public function helloAction($name)
    {
        return $this->render('OpheugeneLibraryBundle:Default:hello.html.twig', array('name' => $name));
    }
}
