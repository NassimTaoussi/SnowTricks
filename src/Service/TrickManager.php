<?php

namespace App\Service;

use App\Entity\Trick;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class TrickManager
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads')]
        private string $uploadsDir
    ) {
    }

    public function add(Trick $trick): void
    {
        $user = $this->security->getUser();
        $trick->setAuthor($user);
        $trick->setCreatedAt(new \DateTimeImmutable('now'));
        $trick->setUpdatedAt(new \DateTimeImmutable('now'));

        foreach ($trick->getPhotos() as $photo) {
            if (null === $photo->getFile()) {
                $trick->removePhoto($photo);
                continue;
            }
            $photo->setName(Uuid::v4().'.'.$photo->getFile()->guessClientExtension());
            $photo->getFile()->move($this->uploadsDir, $photo->getName());
        }

        $trick->setSlug($this->slugger->slug($trick->getName())->lower());

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }

    public function update(Trick $trick): void
    {
        $user = $this->security->getUser();

        foreach ($trick->getPhotos() as $photo) {
            if (null === $photo->getFile() && null === $photo->getId()) {
                $trick->removePhoto($photo);
                continue;
            }
            if (null != $photo->getFile()) {
                $photo->setName(Uuid::v4().'.'.$photo->getFile()->guessClientExtension());
                $photo->getFile()->move($this->uploadsDir, $photo->getName());
            }
        }

        $trick->setSlug($this->slugger->slug($trick->getName())->lower());

        $this->entityManager->flush();
    }
}
