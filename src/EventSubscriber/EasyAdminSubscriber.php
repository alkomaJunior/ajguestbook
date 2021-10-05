<?php

namespace App\EventSubscriber;

use App\Entity\Conference;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    private SluggerInterface $slugger;


    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setConferenceSlugOnPersist'],
            BeforeEntityUpdatedEvent::class   => ['setConferenceSlugOnUpdate'],
        ];
    }

    public function setConferenceSlugOnPersist(BeforeEntityPersistedEvent $persistedEvent){

        $entity = $persistedEvent->getEntityInstance();

        if (!($entity instanceof Conference)) {
            return;
        }

        $slug = $this->slugger->slug( $entity->getCity() . '-' . $entity->getYear())->lower();
        $entity->setSlug($slug);
    }

    public function setConferenceSlugOnUpdate(BeforeEntityUpdatedEvent $updatedEvent){

        $entity = $updatedEvent->getEntityInstance();

        if (!($entity instanceof Conference)) {
            return;
        }

        $slug =$this->slugger->slug($entity->getCity() . '-' . $entity->getYear())->lower();
        $entity->setSlug($slug);
    }
}
