<?php

namespace App\Command;

use App\Entity\User;
use App\Enum\Visibility;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create:user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crée un nouvel utilisateur')
            ->addArgument('login', InputArgument::OPTIONAL, 'Le login de l\'utilisateur')
            ->addArgument('email', InputArgument::OPTIONAL, 'L\'email de l\'utilisateur')
            ->addArgument('password', InputArgument::OPTIONAL, 'Le mot de passe de l\'utilisateur')
            ->addOption('role', null, InputOption::VALUE_OPTIONAL, 'Le rôle de l\'utilisateur (normal/admin)')
            ->addOption('visibility', null, InputOption::VALUE_OPTIONAL, 'Visibilité de l\'utilisateur (visible/masqué)');
    }

    /**
     * @throws RandomException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $login = $input->getArgument('login') ?: $io->ask('Entrez le login de l\'utilisateur');
        $email = $input->getArgument('email') ?: $io->ask('Entrez l\'email de l\'utilisateur');
        $password = $input->getArgument('password') ?: $io->askHidden('Entrez le mot de passe de l\'utilisateur');
        $role = $input->getOption('role') ?: $io->choice('Sélectionnez le rôle de l\'utilisateur', ['normal', 'admin'], 'normal');
        $visibilityOption = $input->getOption('visibility') ?: $io->choice('Sélectionnez la visibilité de l\'utilisateur', ['visible', 'masqué'], 'visible');

        $visibility = $visibilityOption === 'visible' ? Visibility::Public : Visibility::Private;

        $user = new User();
        $user->setLogin($login);
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles($role === 'admin' ? ['ROLE_ADMIN', 'ROLE_USER'] : ['ROLE_USER']);
        $user->setVisibility($visibility);
        $user->setProfileCode($this->generateProfileCode());
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setConnectedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Utilisateur créé avec succès.');

        return Command::SUCCESS;
    }

    /**
     * @throws RandomException
     */
    private function generateProfileCode(): string
    {
        return bin2hex(random_bytes(8));
    }
}
