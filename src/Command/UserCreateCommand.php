<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[AsCommand(
    name: 'user:create',
    description: 'Create a user',
)]
class UserCreateCommand extends Command
{
    public function __construct(private ManagerRegistry $doctrine,
                                private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct("user:create");
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username to create')
            ->addArgument('password', InputArgument::REQUIRED, 'The password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $inputUsername = $input->getArgument('username');
        $inputPassword = $input->getArgument('password');

        // Check if username already exists
        $potentialDuplicate = $this->doctrine->getRepository(User::class)->findOneBy(["username" => $inputUsername]);
        if($potentialDuplicate) {
            $io->error("User is already existing. Pick another username");
            return Command::FAILURE;
        }

        // Create new User object
        $newUser = new User();
        $newUser
            ->setUsername($inputUsername)
            ->setPassword($this->passwordHasher->hashPassword($newUser, $inputPassword));

        // Persist new user
        $this->doctrine->getManager()->persist($newUser);
        $this->doctrine->getManager()->flush();

        $io->success("Successfully created account (${inputUsername}).");

        return Command::SUCCESS;
    }
}
