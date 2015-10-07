<?php

namespace Tigreboite\FunkylabBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tigreboite\FunkylabBundle\Entity\User;
use Tigreboite\FunkylabBundle\Form\UserType;
use Tigreboite\FunkylabBundle\Tools;
use Tigreboite\FunkylabBundle\Annotation\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 *
 * @Route("/translator")
 */
class TranslatorController extends Controller
{

    /**
     *
     * @Route("/download", name="admin_translator_download")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_SUPER_ADMIN') || has_role('ROLE_MODERATOR')")
     */
    public function indexAction()
    {

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Decathlon");

        $phpExcelObject->setActiveSheetIndex(0);

        $em = $this->getDoctrine()->getManager();
        $languages = $em->getRepository('TigreboiteFunkylabBundle:Language')->findBy(array('isenable'=>true),array('name'=>'ASC'));
        $tab = 0;
        foreach($languages as $k=>$language)
        {

            //get All Translation from a language code
            $translations = $em->getRepository('LexikTranslationBundle:Translation')->findBy(array('locale'=>$language->getCode()));
            $content = array();
            foreach($translations as $trans)
            {
                $content[$trans->getTransUnit()->getKey()]=array(
                  $trans->getTransUnit()->getKey(),
                  $trans->getTransUnit()->getDomain(),
                  $trans->getContent()
                );
            }

            ksort($content);

            //Skip if no translation
            if(count($content)==0)
                continue;

            if (isset($active)) {
                $phpExcelObject->createSheet();
            }

            $active = $phpExcelObject->setActiveSheetIndex($tab);
            $active->setTitle($language->getName());

            $active->setCellValue('A1', 'Field');
            $active->setCellValue('B1', 'Domain');
            $active->setCellValue('C1', 'Value');

            $line = 2;
            foreach($content as $trans)
            {
                $active->setCellValue('A'.$line, $trans[0]);
                $active->setCellValue('B'.$line, $trans[1]);
                $active->setCellValue('C'.$line, $trans[2]);
                $line++;
            }
            $tab++;
        }

        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers

        $filename = array(
          'translator',
          date('Y-m-d'),
        );

        $filename = implode('-',$filename).".xls";

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$filename);
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;

        return array();
    }

}
