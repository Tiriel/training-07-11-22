<?php

namespace App\Security\Voter;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends Voter
{
    public const VIEW = 'book.view';
    public const EDIT = 'book.edit';

    public function __construct(
        private AuthorizationCheckerInterface $checker
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($attribute === self::EDIT) {
            return $this->checkEdit($subject, $user);
        }

        return false;
    }

    public function checkEdit(Book $book, UserInterface $user): bool
    {
        return $book->getAddedBy() === $user || $this->checker->isGranted('ROLE_ADMIN');
    }
}