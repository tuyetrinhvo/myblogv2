<?php
/**
 * Class Doc Comment
 *
 * PHP version 7.0
 *
 * @category PHP_Class
 * @package  AppBundle
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Article;
use AppBundle\Entity\Image;
use AppBundle\Entity\Comment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadArticleData
 *
 * @category PHP_Class
 * @package  AppBundle\DataFixtures\ORM
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Function load
     *
     * @param ObjectManager $manager Some argument description
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $image = new Image();
        $image->setExtension('image-01.JPG');
        $image->setAlt('image-01');

        $image1 = new Image();
        $image1->setExtension('image-02.JPG');
        $image1->setAlt('image-02');

        $image2 = new Image();
        $image2->setExtension('image-03.jpg');
        $image2->setAlt('image-03');

        $image3 = new Image();
        $image3->setExtension('image-04.jpg');
        $image3->setAlt('image-04');

        $image4 = new Image();
        $image4->setExtension('image-05.jpg');
        $image4->setAlt('image-05');

        $comment1 = new Comment();
        $comment1->setContent('Premier Commentaire');
        $comment1->setAuthor($this->getReference('myuser'));

        $comment2 = new Comment();
        $comment2->setContent('Deuxième Commentaire');
        $comment2->setAuthor($this->getReference('myadmin'));

        $article1 = new Article();
        $article1->setTitle("Premier Article crée par auteur User");
        $article1->setContent("Premier Article ajoutée par auteur User");
        $article1->setImage($image);
        $article1->addComment($comment2);
        $article1->setAuthor($this->getReference('myuser'));

        $article2 = new Article();
        $article2->setTitle("Deuxième Article crée par auteur User");
        $article2->setContent("Deuxième Article ajoutée par auteur User");
        $article2->setImage($image1);
        $article2->setAuthor($this->getReference('myuser'));

        $article3 = new Article();
        $article3->setTitle("Troisième Article crée par auteur User");
        $article3->setContent("Troisième Article ajoutée par auteur User");
        $article3->setImage($image2);
        $article3->setAuthor($this->getReference('myuser'));

        $article4 = new Article();
        $article4->setTitle("Quatrième Article crée par auteur Admin");
        $article4->setContent("Quatrième Article ajoutée par auteur Admin");
        $article4->setImage($image3);
        $article4->addComment($comment1);
        $article4->setAuthor($this->getReference('myadmin'));

        $article5 = new Article();
        $article5->setTitle("Cinquièmre Article crée par auteur User");
        $article5->setContent("Cinquièmre Article ajoutée par auteur User");
        $article5->setImage($image4);
        $article5->setAuthor($this->getReference('myuser'));

        $manager->persist($article1);
        $manager->persist($article2);
        $manager->persist($article3);
        $manager->persist($article4);
        $manager->persist($article5);
        $manager->flush();
    }

    /**
     * Function getOrder
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
