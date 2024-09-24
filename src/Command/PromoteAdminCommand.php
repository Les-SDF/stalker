<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// TODO: Faire indiquer le login à la place de l'id de l'utilisateur
#[AsCommand(
    name: 'promote:admin',
    description: 'Promote a user to admin',
)]
class PromoteAdminCommand extends Command
{
    public function __construct(private UserRepository $repository,
                                private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument("user_id", InputArgument::REQUIRED, "User ID")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user_id = $input->getArgument("user_id");

        /**
         * @var User $user
         */
        $user = $this->repository->find($user_id);
        if (!$user) {
            $io->error(sprintf("User %s not found", $user_id));
            return Command::INVALID;
        }
        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            $io->error(sprintf("User %s is already admin", $user->getEmail()));
            return Command::INVALID;
        }
        $user->addRoles("ROLE_ADMIN");
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $io->success(sprintf("User %s is now admin", $user->getEmail()));
        return Command::SUCCESS;
    }
}