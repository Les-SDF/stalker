<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use App\Enum\Gender;
use App\Enum\Sexuality;
use App\Enum\Visibility;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $peter = new User();
        $peter->setLogin('poirrierp');
        $peter->setEmail('peter.poirrier@etu.umontpellier.fr');
        $peter->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $peter->setPassword($this->passwordHasher->hashPassword($peter, 'Password123'));
        $peter->setFirstname('Peter');
        $peter->setLastname('Poirrier');
        $peter->setSexuality(Sexuality::Homosexual);
        $peter->setGender(Gender::Esspigender);
        $peter->setVisibility(Visibility::Public);
        $peter->setPhoneNumber('0612345678');
        $peter->setCountryCode('FR');
        $peter->setAddress((new Address())
            ->setStreet('80 rue de la Deletion de Code')
            ->setPostalCode('34200')
            ->setCity('Sète'));
        $manager->persist($peter);

        $quentin = new User();
        $quentin->setLogin('riosserraq');
        $quentin->setEmail('quentin.rios-serra@etu.umontpellier.fr');
        $quentin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $quentin->setPassword($this->passwordHasher->hashPassword($quentin, 'Password123'));
        $quentin->setFirstname('Quentin');
        $quentin->setLastname('Rios-Serra');
        $quentin->setProfilePicture('66f8114a8af3e.jpg');
        $quentin->setSexuality(Sexuality::Neptunic);
        $quentin->setGender(Gender::Aerogender);
        $quentin->setVisibility(Visibility::Public);
        $quentin->setPhoneNumber('0612345678');
        $quentin->setCountryCode('FR');
        $quentin->setAddress((new Address())
            ->setStreet('99 avenue d\'Occitanie')
            ->setPostalCode('34090')
            ->setCity('Montpellier'));
        $manager->persist($quentin);

        $nikhil = new User();
        $nikhil->setLogin('ramn');
        $nikhil->setEmail('nikhil.ram@etu.umontpellier.fr');
        $nikhil->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $nikhil->setPassword($this->passwordHasher->hashPassword($nikhil, 'Password123'));
        $nikhil->setFirstname('Nikhil');
        $nikhil->setLastname('Ram');
        $nikhil->setProfilePicture('1702562455045.png');
        $nikhil->setSexuality(Sexuality::Multisexual);
        $nikhil->setGender(Gender::Autigender);
        $nikhil->setVisibility(Visibility::Public);
        $nikhil->setPhoneNumber('0612345678');
        $nikhil->setCountryCode('IN');
        $nikhil->setAddress((new Address())
            ->setStreet('75 Naan street')
            ->setPostalCode('110')
            ->setCity('New Delhi'));
        $manager->persist($nikhil);

        $thibaut = new User();
        $thibaut->setLogin('audouyt');
        $thibaut->setEmail('thibaut.audouy@etu.umontpellier.fr');
        $thibaut->setRoles(['ROLE_USER']);
        $thibaut->setPassword($this->passwordHasher->hashPassword($thibaut, 'Password123'));
        $thibaut->setFirstname('Thibaut');
        $thibaut->setLastname('Audouy');
        $thibaut->setSexuality(Sexuality::Aroace);
        $thibaut->setGender(Gender::Male);
        $thibaut->setVisibility(Visibility::Public);
        $thibaut->setPhoneNumber('0612345678');
        $thibaut->setCountryCode('FR');
        $thibaut->setAddress((new Address())
            ->setStreet('30 Faculté des Sciences de Montpellier, Place E. Bataillon')
            ->setPostalCode('34095')
            ->setCity('Montpellier'));
        $manager->persist($thibaut);

        $louis = new User();
        $louis->setLogin('texierl');
        $louis->setEmail('louis.texier@etu.umontpellier.fr');
        $louis->setRoles(['ROLE_USER']);
        $louis->setPassword($this->passwordHasher->hashPassword($louis, 'Password123'));
        $louis->setFirstname('Louis');
        $louis->setLastname('Texier');
        $louis->setSexuality(Sexuality::Lesbian);
        $louis->setGender(Gender::Helicoptere_de_combat);
        $louis->setVisibility(Visibility::Public);
        $louis->setPhoneNumber('0612345678');
        $louis->setCountryCode('FR');
        $louis->setAddress((new Address())
            ->setStreet('144 Rue d\'Odin')
            ->setPostalCode('34000')
            ->setCity('Sète'));
        $manager->persist($louis);

        $manager->flush();
    }
}