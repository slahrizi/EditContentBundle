<?php

namespace Tgc\EditContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use Tgc\EditContentBundle\Entity\TextEditable;

use Tgc\EditContentBundle\Form\TextEditableType;


class DefaultController extends Controller
{
  /**
   * @Route("/{edit}", name="indexpage" , requirements={"edit": "|edit|preview"})
   */
  public function indexAction($edit = "")
  {

      $editContentService = $this->get('edit_content');

      $result = $editContentService->getEditContent(['titre','description'],'indexpage', $edit);

      return $this->render('TgcEditContentBundle:Default:index.html.twig', $result);
  }


  /**
   * @Route("/autrepage/{edit}", name="autrepage" )
   */
  public function autrePageAction($edit = "")
  {
    //reucuper mon service 'edit_content'
    $editContentService = $this->get('edit_content');

    $result = $editContentService->getEditContent(['autretitre','autredescription'],'autrepage', $edit);


    return $this->render('TgcEditContentBundle:Default:autrepage.html.twig', $result);

  }

  /**
   * @Route("/edit-text/{slug}", name="edittextpage")
   * @param $slug
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   * @throws \Exception
   */
  public function editTextAction($slug, Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $textEditabl = $em->getRepository(TextEditable::class)->findOneBySlug($slug);

      if (!$textEditabl instanceof TextEditable) {
          throw new \Exception('attention ce n\'est pas une entity');
      }

      $editform = $this->createForm(TextEditableType::class, $textEditabl);

      $editform->handleRequest($request);

      if ($editform->isSubmitted() && $editform->isValid()) {
          $session = $this->get('session');
          $session->set('preview', $textEditabl);

          return $this->redirectToRoute($session->get('origin-page'), [
              'edit' => 'preview']);
      }

      return $this->render('TgcEditContentBundle:Default:edit.html.twig', [
          'editform' => $editform->createView(),

      ]);

  }

  /**
   * @Route("/validateEdit/{valid}", name="validateEditionpage")
   */
  public function validateEditionAction($valid)
  {
      $session = $this->get('session');
      if ($valid){
          $em = $this->getDoctrine()->getManager();

          $textEditpreview = $session->get('preview');

          if (!$textEditpreview instanceof TextEditable ){
              throw new \Exception('attention ce n\'est pas une entity');
          }
          $textEditable = $em->getRepository(TextEditable::class)->find($textEditpreview->getId());
          $textEditable->setText($textEditpreview->getText());
          $em->flush();

      }

      $session->remove('textEditable');

      return $this->redirectToRoute($session->get('origin-page'), [
          'edit' => 'edit' ]);
  }


}
