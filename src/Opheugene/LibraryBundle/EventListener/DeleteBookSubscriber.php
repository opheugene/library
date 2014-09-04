<?php
namespace Opheugene\LibraryBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Opheugene\LibraryBundle\Entity\Book;

class DeleteBookSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postRemove'
        );
    }

    public function postRemove (LifecycleEventArgs $args)
    {
    	$entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        // delete files
        if ($entity instanceof Book)
            $entity->deleteUploadedFiles();
    }

}

?>