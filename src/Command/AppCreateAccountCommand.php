<?php

namespace App\Command;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'App:CreateAccount',
    description: 'Créer un compte',
)]
class AppCreateAccountCommand extends Command
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = new User();
        $profil = new Profil();

        $user->setPassword($this->passwordHasher->hashPassword($user, $_ENV["DEFAULT_PASSWORD"]))
            ->setEmail("matthcollin6@gmail.com")
            ->setRoles(["ROLE_ADMIN"]);

        $profil->setAge(22)->setDescription("changeMe")->setCity("changeMe")->setFirstname("changeMe")->setLastname("changeMe")->setMail("changeMe")->setJob("changeMe")->setPhone("changeMe");

        
        $this->entityManager->persist($profil);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Ajout du compte admin');



        return Command::SUCCESS;
    }
}