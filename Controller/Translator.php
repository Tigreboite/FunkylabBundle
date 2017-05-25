<?php
/**
 * Controller for translatable
 * Automate the update of all fields in every languages.
 */

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Translator extends Controller
{
    /**
     * @param $entity
     * @param $data
     *
     * @return bool
     */
    public function updateLanguageEntity($entity)
    {

        //Updated entity with default language
        $em = $this->getDoctrine()->getManager();
        $default_locale = $this->container->getParameter('locale');

        $entity->setTranslatableLocale($default_locale);

        $em->persist($entity);
        $em->flush();

        $request = $this->get('request');

        //Process languages
        $data = array();
        $languages = $this->getDoctrine()->getManager()->getRepository('TigreboiteFunkylabBundle:Language')->findAll();
        foreach ($languages as $l) {
            $data[$l->getCode()] = $request->get($l->getCode());
        }

        //Process data
        $repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        if ($data[$default_locale]) {
            foreach ($data as $l => $values) {
                foreach ($values as $k => $v) {
                    $repository->translate($entity, $k, $l, $v);
                }
            }

            $em->persist($entity);
            $em->flush();
        }
        if ($entity) {
            $em->refresh($entity);

            return true;
        } else {
            return false;
        }
    }
}
