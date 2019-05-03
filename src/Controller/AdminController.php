<?php

namespace App\Controller;

use App\Form\AdminUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\User;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ComentType;
use App\Form\TagType;
use App\Entity\Post;
use App\Entity\Comment;
use App\Form\PostType;
/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 *
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
    /**
     * @Route("/admin/users", name="app_adminUser")
     */
    public function users()
    {

        $users=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/user.html.twig',[
            'users'=>$users]);
    }
    /**
     * @Route("/admin/users/create", name="app_create")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $user->setIsActive(true);
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);
        $error = $form->getErrors();

        if ($form->isSubmitted() && $form->isValid()) {
            //encriptacion plainpassowrd
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //handle the entities
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success', 'User created'
            );
            return $this->redirectToRoute('app_admin');
        }

        //renderizar el formulario
        return $this->render('admin/edit.html.twig', [
            'error' => $error,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("admin/users/edit/{id}", name="app_edit")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id)
    {
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $titulo= 'usuario';
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);
        $error = $form->getErrors();

        if ($form->isSubmitted() && $form->isValid()) {
            //encriptacion plainpassowrd
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //handle the entities
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $hola="hola";
            $users=$this->getDoctrine()->getRepository(User::class)->findAll();
            return $this->render('user.html.twig',[
                'users'=>$users, 'hola'=>$hola]);
        }

    //renderizar el formulario
    return $this->render('admin/edit.html.twig', ['title'=>$titulo,
    'error' => $error,
    'form' => $form->createView()
    ]);

}

    /**
     * @Route("admin/users/delete/{id}" , name="admin_userDelete")
     * @Method({"DELETE"})
     */
    public function deleteUser(Request $request, $id){

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if($user != $this->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $response = new Response();
            $response->send();


        }
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            return $this->redirectToRoute('app_adminUser', [
                'users' => $users]);



    }
    /**
     * @Route("/admin/post", name="app_allPost")
     */
    public function allPost()
    {
        $posts=$this->getDoctrine()->getRepository(Post::class)->findAll();
        return $this->render('admin/showPost.html.twig',[
            'posts'=>$posts]);
    }
    /**
     * @Route("/admin/post/edit/{id}", name="post_adminEditPost")
     */
    public function editPost(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        $error = $form->getErrors();

        if ($form->isSubmitted() && $form->isValid()) {
            $fechaActual= new \DateTime();
            $post->setModifiedAt($fechaActual);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        //renderizar el formulario
        return $this->render('admin/editarPost.html.twig', [
            'error' => $error,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("post/delete/{id}", name="post_admindelete")
     * @Method({"DELETE"})
     */
    public function deletePost(Request $request, $id){

        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $comments=$this->getDoctrine()->getRepository(Comment::class)->findBy(array('post'=> $post));

        $entityManager = $this->getDoctrine()->getManager();
        if(count($comments)>=1){
            foreach($comments as $comment){
                $entityManager->remove($comment);
            }
        }
        $entityManager->remove($post);
        $entityManager->flush();

        $response = new Response();
        $response->send();



        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

            return $this->redirectToRoute('app_allPost', [
                'posts' => $posts]);


    }
    /**
     * @Route("/admin/tags", name="app_adminTags")
     */
    public function tags()
    {
        $tags=$this->getDoctrine()->getRepository(Tag::class)->findAll();
        return $this->render('admin/tags.html.twig',[
            'tags'=>$tags]);
    }

    /**
     * @Route("admin/tags/delete/{id}", name="tag_delete")
     * @Method({"DELETE"})
     */
    public function deleteTag(Request $request, $id){

        $tag = $this->getDoctrine()->getRepository(Tag::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tag);
        $entityManager->flush();

        $response = new Response();
        $response->send();



        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        return $this->redirectToRoute('app_adminTags', [
            'tags' => $tags]);

    }

    /**
     * @Route("/admin/tags/edit/{id}", name="app_editTag")
     */
    public function editTag(Request $request, $id)
    {
        $tag = $this->getDoctrine()->getRepository(Tag::class)->find($id);
        $titulo= 'tags';

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);
        $error = $form->getErrors();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $tags=$this->getDoctrine()->getRepository(Tag::class)->findAll();
            return $this->redirectToRoute('app_adminTags',[
                'tags'=>$tags]);
        }

        //renderizar el formulario
        return $this->render('admin/edit.html.twig', ['title'=>$titulo,
            'error' => $error,
            'form' => $form->createView()
        ]);

    }
}
