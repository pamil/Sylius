<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\AttributeBundle\Form\Type\AttributeType;

use Gaufrette\Filesystem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class FileAttributeType extends AbstractType
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class)
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();

                if ($data['file'] === null) {
                    $event->setData([]);

                    return;
                }

                $file = $data['file'];

                /** @var UploadedFile $file */
                Assert::isInstanceOf($file, UploadedFile::class);

                if (isset($data['path']) && $this->filesystem->has($data['path'])) {
                    $this->filesystem->delete($data['path']);
                }

                do {
                    $path = sha1(uniqid('', true)) . '.' . $file->getClientOriginalExtension();
                } while ($this->filesystem->has($path));

                $this->filesystem->write(
                    $path,
                    file_get_contents($file->getPathname())
                );

                $event->setData([
                    'path' => $path,
                    'size' => filesize($file->getPath()),
                    'type' =>$file->getClientMimeType(),
                ]);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'label' => false,
            ])
            ->setRequired('configuration')
            ->setDefined('locale_code')
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_attribute_type_file';
    }
}
