<?php
namespace TheCodeine\EditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TheCodeine\EditorBundle\Form\Type\EditorType;

class TestController extends Controller
{
    /**
     * @Template()
     */
    public function testAction()
    {
        $form = $this->createFormBuilder()
            ->add('the_codeine_editor_test', 'editor')
            ->getForm();

        return array(
            'form' => $form->createView()
        );
    }
}
