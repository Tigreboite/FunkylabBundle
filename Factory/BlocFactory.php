<?php

namespace Tigreboite\FunkylabBundle\Factory;

use Tigreboite\FunkylabBundle\Entity\Bloc;
use Doctrine\ORM\PersistentCollection;
use Tigreboite\FunkylabBundle\Manager\ActualityManager;
use Tigreboite\FunkylabBundle\Manager\PageManager;

class BlocFactory
{
    protected $actualityManager;
    protected $pageManager;

    public function __construct(ActualityManager $actualityManager, PageManager $pageManager)
    {
        $this->actualityManager = $actualityManager;
        $this->pageManager = $pageManager;
    }

    public function createBloc($type, $id)
    {
        $bloc = new Bloc();
        $bloc->setType($type);

        if ($type == 'actuality') {
            $bloc->setActuality($this->getBlocParent($type, $id));
        } elseif ($type == 'page') {
            $bloc->setPage($this->getBlocParent($type, $id));
        }

        return $bloc;
    }

    public function getBlocParent($type, $id)
    {
        if ($type == 'actuality') {
            $entity = $this->actualityManager->findOneById($id);
        } elseif ($type == 'page') {
            $entity = $this->pageManager->findOneById($id);
        }

        return isset($entity) ? $entity : false;
    }

    /**
     * Nous permet de récupérer uniquement les blocs affichés en début de page de contenu.
     * Pour cela on récupère les blocs avant le 1er bloc image.
     */
    public function getTopBlocs(PersistentCollection $blocs)
    {
        /*
         * @var PersistentCollection
         */
        if ($blocs->count() == 0) {
            return;
        }

        /*
         * @var $blocs PersistentCollection
         * @var $bloc Bloc
         */
        $topBlocs = array();
        foreach ($blocs as $bloc) {
            if ($bloc->getLayout() != 'bloc-image') {
                $topBlocs[] = $bloc;
            } else {
                break;
            }
        }

        return $topBlocs;
    }

    /**
     * Nous permet de récupérer uniquement les blocs affichés en bas de page
     * et de les hierarchiser par rapport aux blocs Images.
     */
    public function getBottomBlocs(PersistentCollection $blocs)
    {
        if ($blocs->count() == 0) {
            return;
        }

        /*
         * @var $blocs PersistentCollection
         * @var $bloc Bloc
         */
        $bottomBlocs = array();
        $children = array();
        $blocImage = null;

        // Positionnement sur le 1er bloc image.
        $hasBlocImage = false;

        while ($bloctmp = $blocs->next()) {
            if ($bloctmp->getLayout() == 'bloc-image') {
                $bloc = $bloctmp;
                $hasBlocImage = true;
                break;
            }
        }

        if (!$hasBlocImage) {
            return;
        }

        do {
            /*
             * @var Bloc
             */
            if ($bloc->getLayout() == 'bloc-image') {
                $blocImage = $bloc;
            }

            if ($bloc->getLayout() != 'bloc-image') {
                $children[] = $bloc;
            }

            // Fin de construction d'un array
            $lastBloc = $blocs[count($blocs) - 1];

            if (count($children) > 0 && ($bloc->getLayout() == 'bloc-image' || $lastBloc == $bloc)) {
                if ($lastBloc->getLayout() != 'bloc-image') {
                    $bottomBlocs[] = array(
                        'layout' => 'bloc-image',
                        'file' => $blocImage->getFile(),
                        'children' => $children,
                    );
                    $children = array();
                }
            }
        } while ($bloc = $blocs->next());

        return $bottomBlocs;
    }

    public function countBlocs($type, PersistentCollection $blocs)
    {
        $count = 0;
        foreach ($blocs as $bloc) {
            /*
             * @var Bloc
             */
            if ($bloc->getLayout() == $type) {
                ++$count;
            }
        }

        return $count;
    }
}
