<?php
// src/DataFixtures/AdminUserFixtures.php
namespace App\DataFixtures;

use App\Entity\AdminUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $adminUserRepository = $manager->getRepository(AdminUser::class);
        $existingAdminUser = $adminUserRepository->findOneBy(['email' => 'admin@example.com']);

        if (!$existingAdminUser) {
            $adminUser = new AdminUser();
            $adminUser->setEmail('admin@example.com');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $adminUser->setPassword($this->passwordHasher->hashPassword(
                $adminUser,
                'password'
            ));

            $manager->persist($adminUser);
            $manager->flush();
        }
    }
}
