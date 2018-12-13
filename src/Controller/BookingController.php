<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Event;
use App\Form\BookingType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/events/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Event $event, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $booking->setBooker($this->getUser())
                    ->setEvent($event);
            $manager->persist($booking);
            $manager->flush();

            return $this->redirectToRoute('bookig_show', ['id' => $booking->getId()]);
        }

        return $this->render('booking/book.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     * @param Booking $booking
     */
    public function show(Booking $booking) {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
