<?php

namespace dabu\HTMLForm;
/**
 * Form to add comment
 *
 */
class CFormCommentAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $pagekey;
    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($pagekey, $redirect, $acronym, $pagetype, $formid=null)
    {
        parent::__construct(['id' => $formid], [
        	
            'content' => [
                'type'        => 'textarea',
                'label'       => 'Comment',
                'required'    => true,
                'validation'  => ['not_empty'],
                'description' => 'You can use markdown to format the text.'
            ],
            
            'name' => [
                'type'        => 'hidden',
                'value'       => $acronym,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
        
            'submitcomment-'.$formid => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmitComment'],
                'value'     => 'Submit',
            ],
            'reset' => [
                'type'      => 'reset',
                //'callback'  => [$this, 'callbackReset'],
                'value'     => 'Reset',
            ],
            
        ]);
        
        $this->pagekey = $pagekey;
        $this->pagetype = $pagetype;
        $this->redirect = $redirect;
        $this->formid = $formid;
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }
    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitComment()
    {
	
        $now = date('Y-m-d H:i:s');
       if (!empty($_POST['submitcomment-'.$this->formid])) {
	$this->newcomment = new \dabu\Comments\Comments();
        $this->newcomment->setDI($this->di);
        $saved = $this->newcomment->save(array('content' => $this->Value('content'), 'name' => $this->Value('name'), 'pagekey' => $this->pagekey, 'pagetype' => $this->pagetype, 'timestamp' => $now));
	
       // $this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
       }
    
        else return false;
    }

     /**
     * Callback reset
     *
     */
    public function callbackReset()
    {
         $this->redirectTo($this->redirect);
    }


    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
        $this->redirectTo($this->redirect);
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        //$this->redirectTo($this->redirect);
    }
}
