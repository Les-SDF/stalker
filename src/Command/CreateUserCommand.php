<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crée un nouvel utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'L\'email de l\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Le mot de passe de l\'utilisateur')
            ->addOption('role', null, InputOption::VALUE_OPTIONAL, 'Le rôle de l\'utilisateur (normal/administrateur)', 'normal');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = $input->getOption('role');

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles([$role === 'administrateur' ? 'ROLE_ADMIN' : 'ROLE_USER']);

        // Enregistrer l'utilisateur
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur créé avec succès.');

        return Command::SUCCESS;
    }
}
