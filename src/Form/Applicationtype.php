<?php
/**
 * Created by PhpStorm.
 * User: etienne
 * Date: 05/12/18
 * Time: 11:17
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;

class Applicationtype extends AbstractType
{
    /**
     * @param $placeholder
     * @return array
     */
    protected function getConfiguration($placeholder, $options = [])
    {
        return array_merge([
            'label' => false,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}