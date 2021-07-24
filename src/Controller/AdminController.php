<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Rent;
use App\Entity\User;
use App\Form\BookType;
use App\Form\MemberType;
use App\Form\RentType;
use App\Repository\BookRepository;
use App\Repository\RentRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/")
 */

class AdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    //<editor-fold desc="Code dashboardAdmin">
    /**
     * @Route("/", name="dashboardAdmin")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('listRents');
    }
    //</editor-fold>


    //<editor-fold desc="Code Books">
    /**
     * @Route("/books", name="listBooks")
     * @param Request $request
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function listBooks(Request $request, BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        return $this->render('BackOffice/admin/books/listBooks.html.twig', [
            'books'=>$books,
        ]);
    }

    /**
     * @Route("/books/add", name="addBook")
     * @param Request $request
     * @return Response
     */
    public function addBook(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form ->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()){
                $this->manager->persist($book);
                $this->manager->flush();

                return $this->redirectToRoute("listBooks");
            }
        }catch (Exception $ex){
            $this->addFlash('error','error');
        }

        return $this->render('BackOffice/admin/books/addBook.html.twig', [
            'form'=>$form->createView(),
        ]);
    }


    /**
     * @Route("/books/edit/{slug}", name="editBook")
     * @param Book $book
     * @param Request $request
     * @return Response
     */
    public function editBook(Book $book,Request $request): Response
    {
        $form = $this->createForm(BookType::class,$book);
        $form ->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()){

                $this->manager->persist($book);
                $this->manager->flush();

                return $this->redirectToRoute("listBooks");
            }
        }catch (Exception $ex){
            $this->addFlash('error','error');
        }

        return $this->render('BackOffice/admin/books/editBooks.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/book/delete/{slug}" , name="deleteBook")
     * @param Book $book
     * @return RedirectResponse
     */
    public  function deleteCountry(Book $book)
    {
        $this->managerr->remove($book);
        $this->manager->flush();

        return $this->redirectToRoute("listBooks");
    }
    //</editor-fold>




    //<editor-fold desc="Code Member">
    /**
     * @Route("/members", name="listMembers")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function listMembers(Request $request, UserRepository $userRepository): Response
    {

        $members = $userRepository->findUsersByRole("ROLE_MEMBER");
        return $this->render('BackOffice/admin/members/listMembers.html.twig', [
            'members'=>$members,
        ]);
    }

    /**
     * @Route("/members/add", name="addMember")
     * @param Request $request
     * @return Response
     */
    public function addMember(Request $request): Response
    {
        $member = new User();
        $form = $this->createForm(MemberType::class,$member);
        $form ->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()){

                $member->setRoles(['ROLE_MEMBER']);
                $this->manager->persist($member);
                $this->manager->flush();

                return $this->redirectToRoute("listMembers");
            }
        }catch (Exception $ex){
            $this->addFlash('error','error');
        }

        return $this->render('BackOffice/admin/members/addMember.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/members/edit/{slug}", name="editMember")
     * @param User $member
     * @param Request $request
     * @return Response
     */
    public function editMember(User $member,Request $request): Response
    {
        $form = $this->createForm(MemberType::class,$member);
        $form ->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()){

                $this->manager->persist($member);
                $this->manager->flush();

                return $this->redirectToRoute("listMembers");
            }
        }catch (Exception $ex){
            $this->addFlash('error','error');
        }

        return $this->render('BackOffice/admin/members/editMember.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/member/delete/{slug}" , name="deleteMember")
     * @param User $member
     * @return RedirectResponse
     */
    public  function deleteMember(User $member)
    {
        $this->manager->remove($member);
        $this->manager->flush();

        return $this->redirectToRoute("listMembers");
    }
    //</editor-fold>


    //<editor-fold desc="Code Rents">
    /**
     * @Route("/rents", name="listRents")
     * @param Request $request
     * @param RentRepository $rentRepository
     * @return Response
     */
    public function listRents(Request $request, RentRepository $rentRepository): Response
    {

        $rents = $rentRepository->findAll();
        return $this->render('BackOffice/admin/rents/listRents.html.twig', [
            'rents'=>$rents,
        ]);
    }

    /**
     * @Route("/rents/add", name="addRent")
     * @param Request $request
     * @return Response
     */
    public function addRent(Request $request): Response
    {
        $rent = new Rent();
        $form = $this->createForm(RentType::class,$rent);
        $form ->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()){
                $this->manager->persist($rent);
                $this->manager->flush();

                return $this->redirectToRoute("listRents");
            }
        }catch (Exception $ex){
            $this->addFlash('error','error');
        }

        return $this->render('BackOffice/admin/rents/addRent.html.twig', [
            'form'=>$form->createView(),
        ]);
    }


    /**
     * @Route("/rents/edit/{id}", name="editRent")
     * @param Rent $rent
     * @param Request $request
     * @return Response
     */
    public function editRent(Rent $rent,Request $request): Response
    {
        $form = $this->createForm(RentType::class,$rent);
        $form ->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()){
                $this->manager->persist($rent);
                $this->manager->flush();

                return $this->redirectToRoute("listRents");
            }
        }catch (Exception $ex){
            $this->addFlash('error','error');
        }

        return $this->render('BackOffice/admin/rents/editRent.html.twig', [
            'form'=>$form->createView(),
        ]);
    }


    /**
     * @Route("/rent/delete/{id}" , name="deleteRent")
     * @param Rent $rent
     * @return RedirectResponse
     */
    public function deleteRent(Rent $rent)
    {
        $this->manager->remove($rent);
        $this->manager->flush();

        return $this->redirectToRoute("listRents");
    }


    //</editor-fold>
}
