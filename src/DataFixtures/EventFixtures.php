<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Event;
use App\Entity\MeetingPoint;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Julie')
            ->setLastName('Trannois')
            ->setEmail('hamael@hotmail.fr')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setAvatar('https://randomuser.me/api/portraits/women/54.jpg')
            ->setIntro($faker->sentence())
            ->addUserRole($adminRole);
        $manager->persist($adminUser);


        $users = [];
        $genres = ['male', 'female'];

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $avatar = "https://randomuser.me/api/portraits/";
            $avatarId = $faker->numberBetween(1, 99). '.jpg';

            $avatar .= ($genre == 'male' ? 'men/' : 'women/').$avatarId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntro($faker->sentence())
                ->setHash($hash)
                ->setAvatar($avatar);

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <= 30; $i++) {
            $event = new Event();

            $user = $users[mt_rand(0, count($users) - 1)];

            $event->setTitle($faker->sentence())
                ->setDescription($faker->paragraph(10))
                ->setPicture($this->getReference('picture_'. rand(1,9)))
                ->setAuthor($user);

            for ($j = 1; $j <= mt_rand(2, 3); $j++) {
                $meetingPoint = new MeetingPoint();
                $meetingPoint->setPlace($faker->address)
                    ->setTime($faker->dateTime)
                    ->setDetails($faker->paragraph(5))
                    ->setEvent($event);
                $manager->persist($meetingPoint);
            }

            for($j = 1; $j <= mt_rand(0,10); $j++) {
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                $duration = mt_rand(3, 10);
                $endDate = (clone $startDate)->modify("+$duration days");

                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setEvent($event)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setComment($comment);
                $manager->persist($booking);
            }

            $manager->persist($event);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [PictureFixtures::class];
    }
}
