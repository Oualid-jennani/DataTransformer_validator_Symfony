<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MemberTransformer implements DataTransformerInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * BookTransformer constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * transforms a member into a code.
     *
     * @param User|null $value the given Member
     *
     * @return string the user's Code
     */
    public function transform($value)
    {
        if (null === $value || '' === $value) {
            return '';
        }

        return $value->getCode();
    }

    /**
     * transforms the code to a member.
     *
     * @param string $value the code
     *
     * @return User|null the user instance
     */
    public function reverseTransform($value)
    {
        // It's use when the field is optional
        if (!$value) {
            return;
        }

        //find the object using the entity manager
        /** @var  User $member */
        $member = $this->repository->findOneBy(["code" => $value]);
        if (null === $member) {
            $exceptionMessage = \sprintf(
                'A Adherent with the given code "%s" does not exist',
                $value
            );
            $violationError = 'Adherent with code {{ code }} was not found';
            $failure = new TransformationFailedException($exceptionMessage);
            $failure->setInvalidMessage($violationError, [
                '{{ code }}' => $value,
            ]);
            throw $failure;
        }

        return $member;
    }

}
