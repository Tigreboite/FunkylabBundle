<?php

namespace Tigreboite\FunkylabBundle\Entity;

use Tigreboite\FunkylabBundle\Entity\BaseRepository;
use Tigreboite\FunkylabBundle\Entity\Activity;

class ActivityRepository extends BaseRepository
{

    /**
     * @return mixed|string
     */
    public function getMessage($activity)
    {
        global $kernel;
        if($kernel instanceOf \AppCache) $kernel = $kernel->getKernel();
        $container = $kernel->getContainer();
        $router = $container->get('router');

        $this->em = $this->getEntityManager();
        $activity->setEntityManager($this->em);

        $string     = $activity->getStringActivity();

        if(strstr($string,'!user'))
        {
            if($user       = $activity->getUser())
            {
                $user_url   = $router->generate('admin_user_edit',array('id'=>$user->getId()));
                $link       = '<a href="#" data-toggle="modal" data-url="'.$user_url.'">'.htmlentities((string)$user).'</a>';
                $string     = str_replace('!user', $link, $string);
            }else{
                $string     = str_replace('!user', "<strong>Anonymous</strong>", $string);
            }
        }

        if($activity->getActivity()==$activity::USER_IDEA_COMMENT) {
            if($comment  = $activity->getEntity())
            {
                $com_url    = $router->generate('admin_ideacomment_edit',array('id'=>$comment->getId()));
                $linkCom    = '<a href="#" data-toggle="modal" data-url="'.$com_url.'">' . htmlentities((string)$comment) . '</a>';
                $string     = str_replace('!comment', $linkCom, $string);
                if($idea  = $comment->getIdea())
                {
                    $idea_url = $router->generate('admin_idea_edit',array('id'=>$idea->getId()));
                    $linkIdea = '<a href="#" data-toggle="modal" data-url="'.$idea_url.'">' . htmlentities((string)$idea) . '</a>';
                    $string   = str_replace('!idea', $linkIdea, $string);
                }
            }

        }elseif($activity->getActivity()==$activity::USER_ABUSE) {
            if($abuse = $activity->getEntity())
            {
                $abuse_url      = $router->generate('admin_abuse_edit',array('id'=>$abuse->getId()));
                $abuse_link     = '<a href="#" data-toggle="modal" data-url="'.$abuse_url.'">'.htmlentities((string)$abuse).'</a>';
                $string = str_replace('!abuse', $abuse_link, $string);
                if($idea = $abuse->getIdea())
                {
                    $idea_url = $router->generate('admin_idea_edit',array('id'=>$idea->getId()));
                    $idea_link   = '<a href="#" data-toggle="modal" data-url="'.$idea_url.'">'.htmlentities((string)$idea).'</a>';
                    $string = str_replace('!idea', $idea_link, $string);
                }
            }
        }elseif($activity->getActivity()==$activity::USER_BLOG_COMMENT) {
            if($comment  = $activity->getEntity())
            {
                $com_url    = $router->generate('admin_blogcomment_edit',array('id'=>$comment->getId()));
                $linkCom    = '<a href="#" data-toggle="modal" data-url="'.$com_url.'">' . htmlentities((string)$comment) . '</a>';
                $string   = str_replace('!comment', $linkCom, $string);
                if($blog = $comment->getBlog())
                {
                    $blogurl  = $router->generate('admin_blog_edit',array('id'=>$blog->getId()));
                    $linkBlog = '<a href="#" data-toggle="modal" data-url="'.$blogurl.'">' . (string)$blog . '</a>';
                    $string   = str_replace('!blog', $linkBlog, $string);
                }
            }

        }elseif($activity->getActivity()==$activity::USER_IDEA_QUESTION){
            if($answer = $activity->getEntity())
            {
               $idQ = $answer->getQuestionnaireQuestion();
               $question  = $answer->getQuestionnaireQuestion();
                $questionnaire = $question->getQuestionnaire();

               $answer_txt = $answer->getReponse(); 
               foreach ($answer->getQuestionnaireQuestionReponseLang() as $answer_lang) {
                   if($questionnaire->getLanguage()->getCode()==$answer_lang->getLanguage()->getCode())
                   {
                       $answer_txt = $answer_lang->getReponse();
                   }
               }

               $answer_url = $router->generate('admin_questionnairequestionreponse_edit',array('id'=>$answer->getId()));
               $linkAnswer = '<a href="#" data-toggle="modal" data-url="'.$answer_url.'">' . (string)strip_tags($answer_txt) . '</a>';
               $string   = str_replace('!answer', $linkAnswer, $string);


               if($question)
               {
                   $question_txt = "";
                   foreach ($question->getQuestionnaireQuestionLang() as $question_lang) {
                    if ($questionnaire->getLanguage()->getCode() == $question_lang->getLanguage()->getCode()) {
                        $question_txt = $question_lang->getTitle();
                    }
                   }
                   $question_url = $router->generate('admin_questionnairequestion_edit',array('id'=>$question->getId()));
                   $linkQuestion = '<a href="#" data-toggle="modal" data-url="'.$question_url.'">' . (string)$question_txt . '</a>';
                   $string   = str_replace('!question', $linkQuestion, $string);

                   $idea  = $question->getQuestionnaire()->getIdea();
                   if($idea)
                   {
                       $idea_url = $router->generate('admin_idea_edit',array('id'=>$idea->getId()));
                       $linkIdea  = '<a href="#" data-toggle="modal" data-url="'.$idea_url.'">' . (string)$idea . '</a>';
                       $string   = str_replace('!idea', $linkIdea, $string);
                   }
               }
            }
        }else{
            if(strstr($string,'!idea'))
            {
                if($idea = $activity->getEntity())
                {
                    $idea_url = $router->generate('admin_idea_edit',array('id'=>$idea->getId()));
                    $link   = '<a href="#" data-toggle="modal" data-url="'.$idea_url.'">'.htmlentities((string)$idea).'</a>';
                    $string = str_replace('!idea', $link, $string);
                }
            }
        }

        return $string;
    }

    public function findDataTable($columns, $start, $length, $statut,$order_column, $order_dir, $where_spe = array(), $locale="en")
    {
        $columuns_select = "";
        $first = true;

        $qb = $this->createQueryBuilder('d');

        // Select columns
        foreach ($columns as &$col) {
            if(!empty($col['name'])) {
                if(!$first) $columuns_select .= ',';

                // Check join()
                if(strpos($col['name'], '.') !== false) {
                    $cut = explode('.', $col['name']);
                    $table = $cut[0];
                    $field = $cut[1];

                    $qb->leftJoin('d.'.$table, $table);
                    $qb->addSelect($table.'.'.$field.' as data_'.$col['data']);

                    $col['name'] = $table.'.'.$field;
                }
                elseif(!empty($col['spe']) && $col['spe'] == true) {

                    // SPE Count OneToMany
                    if(isset($col['count_one_to_many']) && $col['count_one_to_many'])
                    {
                        $qb->addSelect('COUNT('.ucfirst($col['name']).') as data_'.$col['data'])
                          ->leftJoin('d.'.$col['name'], ucfirst($col['name']))
                          ->addGroupBy('d.id');

                        $col['name'] = 'data_'.$col['data'];
                        $col['nosearch'] = true;
                    }

                    // SPE OneToMany GROUP CONCAT
                    if(isset($col['group_concat_one_to_many']) && $col['group_concat_one_to_many'])
                    {
                        $field = ucfirst($col['table']).'.'.$col['field'];
                        $qb->addSelect('GROUP_CONCAT('.$field.') as data_'.$col['data'])
                          ->leftJoin('d.'.$col['table'], ucfirst($col['table']))
                          ->addGroupBy('d.id');

                        $col['name'] = 'data_'.$col['data'];
                        $col['nosearch'] = true;
                    }

                }
                elseif($col['name'] == 'action') {
                    $qb->addSelect('d.id');
                    $col['name'] = 'd.id';
                }
                else {
                    $qb->addSelect('d.'.$col['name'].' as data_'.$col['data']);
                    $col['name'] = 'd.'.$col['name'];
                }
            }

            $first = false;
        }

        $countAllDatas = count($qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));

        // Filter Statut
        $qb->andWhere('d.statut = :statut');
        $qb->setParameters(array(':statut'=>$statut ? $statut : Activity::STATUT_UNSEEN));

        // Filter Type
//        if($options['type'] && $options['type']!='')
//        {
//            var_dump($options['type'] );
//            $qb->andWhere('d.entityType = :type');
//            $parameters[]=array(':type'=>'idea');
//        }


        // WHERE SPE
        if(!empty($where_spe)) {
            foreach ($where_spe as $where) {
                $qb->andWhere($where);
            }
        }

        $countFiltered = count($qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));

        // Order
        if($columns[$order_column]['name'] != 'action') {
            $qb->orderBy($columns[$order_column]['name'], strtoupper($order_dir));
        }

        $datas = $qb->setMaxResults($length)
          ->setFirstResult($start)
          ->getQuery()
          ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $result = array();
        foreach ($datas as $data) {
            $index = 0;
            unset($data[0]);
            foreach ($data as $value) {
                $tmp[$index] = (empty($value) ? "" : $value);
                $index++;
            }
            $result[] = $tmp;
        }


        foreach($result as $k=>$r)
        {
            $id = $result[$k][0];
            $entity = $this->find($id);
            $result[$k][1]=$this->getMessage($entity);
            $result[$k][2]=$entity->statutString[$entity->getStatut()];
            $result[$k][3]=$entity->getCreatedAt()->format('d/m/Y H:i:s');
        }
        //
        //data[1]=
          //Date
//        $data[5]=$data[5]->format('d/m/Y');
//        $data[6]="ok";

        $result['count_all'] = $countAllDatas;
        $result['count_filtered'] = $countFiltered;

        return $result;
    }
}
