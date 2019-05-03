<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 23/01/19
 * Time: 17:55
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
Class HomeController extends AbstractController
{
    /**
     * @Route ("/", name="app_homepage")
     */
    public function homepage(){
        $posts=$this->getDoctrine()->getRepository(Post::class)->findAll();
        return $this->render('home/home.html.twig', [
            'posts'=>$posts]);
    }
}