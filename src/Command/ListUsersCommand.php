<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:list-users',
    description: 'Liste des utilisateurs',
    hidden: false,
    aliases: ['app:list-users']
)]
class ListUsersCommand extends Command
{

    private $em;
    protected static $defaultName = 'app:list-users';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('List all users')
            ->setHelp('This command allows you to list all users in the database');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    $userRepository = $this->em->getRepository(User::class);

    $users = $userRepository->findAll();
    
    foreach ($users as $user) {
        $output->writeln($user->getEmail());
    }

        return Command::SUCCESS;
    }
}
