<?php

namespace Opheugene\LibraryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $book = $builder->getData();
        $builder
            ->add("name", null, array(
                "label" => "Название",
                "data" => $book->getName() ?: $options["name"]
            ))
            ->add("author", null, array(
                "label" => "Автор",
                "data" => $book->getAuthor() ?: $options["author"]
            ))
            ->add("coverPath", null, array("label" => "Обложка"))
            ->add("filePath", null, array("label" => "Файл"))
            ->add("read", null, array(
                'widget' => 'single_text',
                "label"  => "Дата прочтения",
                "data" => $book->getRead() ?: $options["read"]
            ))
            ->add("download", null, array("label" => "Разрешить скачивание"))
            ->add("delete", "checkbox", array("label" => "Удалить книгу", "mapped" => false))
            ->add("Сохранить", "submit");

        if ($book->getCover()) {
            $builder->add("delete_cover", "checkbox", array("label" => "Удалить", "mapped" => false));
        }

        if ($book->getFile()) {
            $builder->add("delete_file", "checkbox", array("label" => "Удалить", "mapped" => false));
        }
    }

    public function getName()
    {
        return 'book';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Opheugene\LibraryBundle\Entity\Book',
            'name' => 'Книга',
            'author' => 'Автор',
            'read' => $date = new \DateTime("now")
        ));
    }
}
