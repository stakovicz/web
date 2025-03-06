<?php

declare(strict_types=1);

namespace AppBundle\Event\Form;

use AppBundle\Event\Model\Event;
use AppBundle\Event\Model\Repository\EventRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCompareSelectType extends AbstractType
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $eventId = $builder->getData()['id'] ?? null;

        $builder
            ->add('compared_event_id', ChoiceType::class, [
                'choices' => $this->buildChoices($eventId),
            ])
            ->add('id', HiddenType::class)
            ->setMethod(Request::METHOD_GET)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    private function buildChoices($eventId): array
    {
        $events = $this->eventRepository->getAllEventsExcept($eventId);

        $choices = [];
        /** @var Event $event */
        foreach ($events as $event) {
            $choices[$event->getTitle()] = (int) $event->getId();
        }
        return $choices;
    }
}
