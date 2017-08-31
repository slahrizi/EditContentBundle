<?php

namespace  tgc\EdiContentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use tgc\EdiContentBundle\Entity\TextEditable;


class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
       $contents = [
        [ 'slug' => 'titre'  , 'text' => 'mon premier titre Fixtures'],
        [ 'slug' => 'description'  , 'text' => 'une description tres simple from Fixtures'],
        [ 'slug' => 'autretitre'  , 'text' => 'autre titre Fixtures'],
        [ 'slug' => 'autredescription'  , 'text' => 'autre description tres simple from Fixtures'],
      ];

       foreach ($contents as $content) {

         $textEditable = new TextEditable();

         $textEditable->setSlug($content['slug']);
         $textEditable->setText($content['text']);

         $manager->persist($textEditable);
       }


        $manager->flush();
    }
}
