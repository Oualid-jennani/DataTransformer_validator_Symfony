<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class BookTransformer implements DataTransformerInterface
{
    /**
     * @var BookRepository
     */
    private $repository;

    /**
     * BookTransformer constructor.
     *
     * @param BookRepository $repository
     */
    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * transforms a product into a barcode.
     *
     * @param Book|null $value the given book
     *
     * @return string the book's Ean13
     */
    public function transform($value)
    {
        if (null === $value || '' === $value) {
            return '';
        }

        return $value->getEan13();
    }

    /**
     * transforms the ean13 to a book.
     *
     * @param string $value the ean13
     *
     * @return Book|null the book instance
     */
    public function reverseTransform($value)
    {
        // It's use when the field is optional
        if (!$value) {
            return null;
        }


        //find the object using the entity manager
        /** @var  Book $book */
        $book = $this->repository->findOneBy(["ean13" => $value]);
        if (null === $book) {
            $exceptionMessage = \sprintf(
                'A book with the given ean13 "%s" does not exist',
                $value
            );
            $violationError = 'Book with Ean13 {{ ean13 }} was not found';
            $failure = new TransformationFailedException($exceptionMessage);
            $failure->setInvalidMessage($violationError, [
                '{{ ean13 }}' => $value,
            ]);
            throw $failure;
        }

        return $book;
    }
}
