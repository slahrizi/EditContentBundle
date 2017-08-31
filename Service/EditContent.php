<?php


namespace tgc\EditContentBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

use tgc\EditContentBundle\Entity\TextEditable;

class EditContent
{

  private $session;
  private $em;

   public function __construct(SessionInterface $session, EntityManagerInterface $em)
   {
       $this->session = $session;
       $this->em = $em;
   }


   public function getEditContent($slugs, $currentRout ,$mode)
   {
     $repository = $this->em->getRepository(TextEditable::class);
     $textEditables = [];

     foreach ($slugs as  $slug) {
       $textEditables[$slug] = $repository->findOneBySlug($slug);
     }

     if ($mode == "edit") {
         $this->session->set("origin-page", $currentRout);
     }
     if ($mode == "preview") {

         $textEditablepreview = $this->session->get('preview');

         if (!$textEditablepreview instanceof TextEditable) {
             throw new \Exception('attention ce n\'est pas une entity');
         }

         $slug = $textEditablepreview->getSlug();

         if (array_key_exists($slug , $textEditables)) {

            $textEditables[$slug] = $textEditablepreview;
         }

     }

     $modes = [
       'edit' => ($mode == 'edit'),
       'preview' => ($mode == 'preview'),
     ];

     return array_merge($textEditables, $modes);
   }


}
