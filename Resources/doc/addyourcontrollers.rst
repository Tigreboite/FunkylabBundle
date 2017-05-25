####################
Add your controllers
####################

To extend the admin, create a controller in your own bundle :


  use Tigreboite\FunkylabBundle\Annotation\Menu;
  use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

Configure with annotation your action's controller

  /**
   * Lists all Language entities.
   *
   * @Route("/", name="admin_language")
   * @Method("GET")
   * @Template()
   * @Menu("Languages", dataType="string", icon="fa-flag", groupe="CMS")
   * @Security("has_role('ROLE_SUPER_ADMIN') || has_role('ROLE_MODERATOR')")
   */
   public function indexAction()
   {
       return array();
   }

Menu : name, icon : image to display in admin, groupe, tab where to put this action
Security : Role ROLE_MODERATOR, ROLE_BRAND, ROLE_USER, ROLE_ADMIN

don't forget to add the global route to your controller to be included in the admin.

  /**
   * Language controller.
   *
   * @Route("/admin/language")
   */
  class LanguageController extends Controller

By default the template twig need to be in

  src/AppBundle/Resources/views/yourcontroller/action/index.html.twig

or you can set your template name and path in annotation
