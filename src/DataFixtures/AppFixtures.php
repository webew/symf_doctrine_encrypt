<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $client = new Client();
        $client->setSociete('Jokes Cie');
        $client->setNom('GOLADE');
        $client->setPrenom('Larry');
        $client->setEmail('larry.golade@jokescie.com');           
        $manager->persist($client);

        $user = new User();
        $user->setUsername('admin');
        $user->setRoles( array_unique( ['ROLE_ADMIN']) );
        $password = $this->passwordEncoder->encodePassword($user, 'test');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
