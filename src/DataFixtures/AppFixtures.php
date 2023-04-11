<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Post;
use App\Entity\Project;
use App\Entity\ProjectType;
use App\Entity\Role;
use App\Entity\Sector;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create('fr_FR');

        foreach (['ROLE_USER' => 'Utilisateur', 'ROLE_ADMIN' => 'Administrateur']  as $role => $title) {
            $manager->persist(
                $role = (new Role())
                    ->setName($title)
                    ->setTitle($role)
            );
        }
        $user = (new User())
            ->setName('Administrateur par défaut')
            ->setPseudo('administrateur')
            ->setRole($role);
        $user->setPassword($this->hasher->hashPassword($user, 'administrateur'));
        $manager->persist($user);

        // $types = [];
        // $max = rand(4, 8);

        // foreach (['Construction Batiment', "Adduction d'Eau", "Amenagement Routier", "Gestion Caniveaux"] as $ty) {
        //     $manager->persist(
        //         $type = (new ProjectType)
        //             ->setName($ty)
        //     );
        //     $types[] = $type;
        // }

        // $sectors = [];
        // $max = rand(4, 8);
        // foreach (['Mekouda', "Temkrou", "Katongui", "Samba", 'Dankrob'] as $sec) {
        //     $manager->persist(
        //         $sector = (new Sector)
        //             ->setName($sec)
        //             ->setDescription(rand(2, 3) == 2 ? $faker->words(2, true) : null)
        //     );
        //     $sectors[] = $sector;
        // }

        // $companies = [];

        // foreach (['Markip S.A', "Construction BTP", "Project Leader Manager", "Albar & Fils", "Sankour Batiment", "MKD Ltd", "Africa Builder"] as $comp) {
        //     $manager->persist(
        //         $company = (new Company)
        //             ->setName($comp)
        //             ->setPhoneNumber(rand(0, 1) == 1 ? "+240" . $faker->numberBetween(222123123, 222987988) : null)
        //             ->setAddress(rand(2, 3) == 2 ? $faker->address() : null)
        //             ->setDescription(rand(2, 3) == 2 ? $faker->sentence() : null)
        //     );
        //     $companies[] = $company;
        // }
        // $number = 1;
        // $max = rand(85, 100);
        // foreach (range(1, $max) as $pro) {
        //     $project = (new Project())
        //         ->setName($faker->words(4, true))
        //         ->setCost($faker->numberBetween(1500, 15000) * 500)
        //         ->setType($types[rand(0, count($types) - 1)])
        //         ->setSector($sectors[rand(0, count($sectors) - 1)])
        //         ->setCompany($companies[rand(0, count($companies) - 1)])
        //         ->setNumber($number++)
        //         ->setStartAt($date = $faker->dateTime())
        //         ->setPresentedAt(rand(1, 3) >= 2 ? new \DateTimeImmutable($date->format('Y-m-d')) : null);
        //     if ($project->getPresentedAt() and rand(0, 1) === 1) {
        //         $project->setEndAt($date);
        //     }
        //     if ($project->getEndAt() !== null) {
        //         $project->setIsActive(true);
        //     }
        //     $manager->persist($project);
        // }

        $manager->flush();
    }
}
