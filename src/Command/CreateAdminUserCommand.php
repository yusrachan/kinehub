<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminUserCommand extends Command
{

    protected static $defaultName = 'app:create-admin-user';

    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $user->setEmail('y.arigui99@gmail.com');
        $user->setNom('Arigui');
        $user->setPrenom('Yusrâ');
        $user->setDateNaissance(new \DateTime('1999-06-04'));
        $user->setAdresse('78 rue de Venise');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'unmdpfort'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur administrateur créé avec succès.');

        return Command::SUCCESS;
    }
}
