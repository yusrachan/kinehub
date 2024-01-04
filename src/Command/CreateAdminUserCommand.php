<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminUserCommand extends Command
{

    //php bin/console app:create-admin-user email_de_l_admin@example.com
    protected static $defaultName = 'app:create-admin-user';

    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {

        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'L\'email de l\'administrateur')
            ->addArgument('nom', InputArgument::REQUIRED, 'Le nom de l\'administrateur')
            ->addArgument('prenom', InputArgument::REQUIRED, 'Le prénom de l\'administrateur')
            ->addArgument('dateNaissance', InputArgument::REQUIRED, 'La date de naissance de l\'administrateur')
            ->addArgument('adresse', InputArgument::REQUIRED, 'L\'adresse de l\'administrateur')
            ->setDescription('Crée un nouvel utilisateur administrateur.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $email = $input->getArgument('email');
        $nom = $input->getArgument('nom');
        $prenom = $input->getArgument('prenom');
        $dateNaissance = $input->getArgument('dateNaissance');
        $adresse = $input->getArgument('adresse');

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($existingUser) {
            $output->writeln('Un utilisateur avec cet e-mail existe déjà.');
            return Command::FAILURE;
        }
        
        $user = new User();
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setDateNaissance(new \DateTime($dateNaissance));
        $user->setAdresse($adresse);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'unmdpfort'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur administrateur créé avec succès.');

        return Command::SUCCESS;
    }
}
