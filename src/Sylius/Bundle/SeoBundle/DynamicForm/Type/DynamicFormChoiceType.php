<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SeoBundle\DynamicForm\Type;

use Sylius\Bundle\SeoBundle\DynamicForm\DynamicFormsChoicesMapInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class DynamicFormChoiceType extends AbstractType
{
    /**
     * @var DynamicFormsChoicesMapInterface
     */
    protected $dynamicFormsChildrenMap;

    /**
     * @param DynamicFormsChoicesMapInterface $dynamicFormsChildrenMap
     */
    public function __construct(DynamicFormsChoicesMapInterface $dynamicFormsChildrenMap)
    {
        $this->dynamicFormsChildrenMap = $dynamicFormsChildrenMap;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $availableFormsNames = $this->dynamicFormsChildrenMap->getFormsNamesByGroup($options['group']);

        $this->addAvailableFormsField($builder, $availableFormsNames);
        $this->addAvailableFormsPrototypes($builder, $availableFormsNames);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->exportAvailableFormsPrototypesToView($view, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'inherit_data' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_dynamic_form';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string[] $availableFormsNames
     */
    private function addAvailableFormsField(FormBuilderInterface $builder, $availableFormsNames)
    {
        $choices = [];
        foreach ($availableFormsNames as $availableFormName) {
            $choices[$availableFormName] = $availableFormName;
        }

        $builder->add('_form', 'choice', [
            'choices' => $choices,
            'mapped' => false,
            'required' => false,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string[] $availableFormsNames
     */
    private function addAvailableFormsPrototypes(FormBuilderInterface $builder, $availableFormsNames)
    {
        $prototypes = [];
        foreach ($availableFormsNames as $availableFormName) {
            $prototypes[$availableFormName] = $builder->create($builder->getName(), $availableFormName)->getForm();
        }

        $builder->setAttribute('prototypes', $prototypes);
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     */
    private function exportAvailableFormsPrototypesToView(FormView $view, FormInterface $form)
    {
        /** @var FormInterface[] $prototypes */
        $prototypes = $form->getConfig()->getAttribute('prototypes');

        $view->vars['prototypes'] = [];
        foreach ($prototypes as $type => $prototype) {
            $view->vars['prototypes'][$type] = $prototype->createView($view);
        }
    }
}