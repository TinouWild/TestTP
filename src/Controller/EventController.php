<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\MeetingPoint;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/events", name="events_index")
     * @IsGranted("ROLE_USER")
     */
    public function index(EventRepository $event)
    {
        $events = $event->findAll();
        return $this->render('event/index.html.twig',
            ['events' => $events]
        );
    }

    /**
     * @Route("/events/new", name="events_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            foreach($event->getMeetingPoints() as $meetingPoint) {
                $meetingPoint->setEvent($event);
                $manager->persist($meetingPoint);
            }

            $event->setAuthor($this->getUser());

            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success',
                "Your event {$event->getTitle()} is created ! Please invite your friends."
            );

            return $this->redirectToRoute('events_show', [
                'slug' => $event->getSlug()
            ]);
        }

        return $this->render('event/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("events/{slug}/edit", name="events_edit")
     * @return Response
     * @Security("is_granted('ROLE_USER') and user === event.getAuthor()", message="Is not your fucking event !")
     */
    public function edit(Event $event, Request $request, ObjectManager $manager) {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            foreach($event->getMeetingPoints() as $meetingPoint) {
                $meetingPoint->setEvent($event);
                $manager->persist($meetingPoint);
            }
            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success',
                "Your event {$event->getTitle()} is updated !"
            );

            return $this->redirectToRoute('events_show', [
                'slug' => $event->getSlug()
            ]);
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/events/{slug}", name="events_show")
     * @IsGranted("ROLE_USER")
     */
    public function show(Event $event) : Response
    {
        return $this->render('event/show.html.twig',
            ['event' => $event]
        );
    }

    /**
     * @Route("/events/{slug}/delete", name="events_delete")
     * @Security("is_granted('ROLE_USER') and user == event.getAuthor()")
     * @param Event $event
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete (Event $event, ObjectManager $manager) : Response
    {
        $manager->remove($event);
        $manager->flush();

        $this->addFlash(
            'success',
            'Your event is deleted !'
        );

        return $this->redirectToRoute("events_index");
    }
}
