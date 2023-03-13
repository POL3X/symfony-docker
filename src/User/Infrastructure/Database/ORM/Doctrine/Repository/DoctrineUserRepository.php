<?php

namespace App\User\Infrastructure\Database\ORM\Doctrine\Repository;

use App\User\Domain\Interfaces\DoctrineUserRepositoryInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Exceptions\UserNotFoundException;
use App\User\Infrastructure\Database\ORM\Doctrine\Entity\DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineUserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, DoctrineUserRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
      parent::__construct($registry, DoctrineUser::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
      
        $doctrineUser = DoctrineUser::createFromDomainUser($entity);
        $this->getEntityManager()->persist($doctrineUser);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $doctrineUser = DoctrineUser::createFromDomainUser($entity);
        $this->getEntityManager()->remove($doctrineUser);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findOneByEmail(string $value): User
    {
        $doctrineUser = $this->createQueryBuilder('u')
           ->andWhere('u.email = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult();

       
        if(null == $doctrineUser){
            throw new UserNotFoundException();
        }

        $user = User::createFromDoctrineUser($doctrineUser);
        return $user;
   }
}
