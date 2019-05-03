<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\PostType;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\User;
use App\Form\ComentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    /**
     * @Route("mispost", name="app_post")
     */
    public function HomePost()
    {
        $userid= $this->getUser();
        $posts=$this->getDoctrine()->getRepository(Post::class)->findBy(array('user'=> $userid));
        return $this->render('post/index.html.twig',[
            'posts'=>$posts]);
    }



    /**
     * @Route("/post/new", name="new_post")
     */
    public function newPost(Request $Request)
    {
        $post=new Post();
        $userid= $this->getUser();
        //crear form
        $form = $this->createForm(PostType::class, $post);
        $user = $this->getDoctrine()->getRepository(User::class)->find($userid);
        $post->setUser($userid);
        $post->setAuthor($user->getUsername());

        //handle the request
        $form->handleRequest($Request);
        $error = $form->getErrors();
        if($form->isSubmitted()&& $form->isValid()){
            $post=$form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('app_homepage');
        }
        //render the form
        return $this->render('post/post.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/post/edit/{id}", name="post_edit")
     */
    public function edit(Request $request, $id)
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

            return $this->redirectToRoute('app_homepage');;
        }

        //renderizar el formulario
        return $this->render('post/editar.html.twig', [
            'error' => $error,
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("post/show/{id}", name="post_show")
     */
    public function show(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $user= $this->getDoctrine()->getRepository(User::class)->findAll();
        $comentariosActuales = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('post' => $post));
        $comentario = new Comment();
        $form = $this->createForm(ComentType::class, $comentario);

        $form->handleRequest($request);
        $error = $form->getErrors();

        if ($form->isSubmitted() && $form->isValid()) {
            $comentario->setUser($this->getUser());
            $comentario->setPost($post);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comentario);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage');
        }
        //renderizar el formulario
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'comment' => $comentariosActuales
        ]);

    }
    /**
     * @Route("post/delete/{id}", name="post_adminedit")
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
        if (is_granted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_allPost', [
                'posts' => $posts]);
        }else{
            return $this->redirectToRoute('app_post', [
                'posts' => $posts]);
        }

    }
}
