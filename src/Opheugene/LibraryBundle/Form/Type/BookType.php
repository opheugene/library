<?php

namespace Opheugene\LibraryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", null, array("label"  => "Название"))
            ->add("author", null, array("label"  => "Автор"))
            //->add("cover", "file", array("label"  => "Обложка", "data_class" => null))
            ->add("coverPath", null, array("label"  => "Обложка"))
            //->add("file", "text", array("label"  => "Файл"))
            ->add("filePath", null, array("label"  => "Файл"))
            ->add("read", null, array('widget' => 'single_text', "label"  => "Дата прочтения"))
            ->add("download", null, array("label"  => "Разрешить скачивание"))
            ->add("Сохранить", "submit");
    }

    public function getName()
    {
        return 'book';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Opheugene\LibraryBundle\Entity\Book',
        ));
    }
}
