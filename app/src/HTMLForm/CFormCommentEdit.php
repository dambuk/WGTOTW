<?php

namespace dabu\HTMLForm;

/**
 * Form to edit comment
 *
 */
class CFormCommentEdit extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $id; 
    private $pagekey;
    private $redirect;

    /**
     * Constructor
     *
     */
    public function __construct($id, $content, $name, $pagekey, $redirect)
    {
        parent::__construct([], [
        	
        	'content' => [
                'type'        => 'textarea',
                'label'       => 'Comment',
                'value'       =>  $content,
                'required'    => true,
                'validation'  => ['not_empty'],
                'description' => 'You can use markdown to format the text.'
            ],
            
            'name' => [
                'type'        => 'hidden',
                'value'       =>  $name,
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
                       
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Submit',
            ],
            'reset' => [
                'type'      => 'reset',
                'value'     => 'Reset',
            ],
            
            'delete' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackDelete'],
                'value'     => 'Delete',
            ],
            
        ]);
        
        $this->id = $id;
        $this->pagekey = $pagekey;
        $this->redirect = $redirect;
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
    public function callbackSubmit()
    {

        $now = date('Y-m-d H:i:s');
        
        $this->comment = new \dabu\Comments\Comments();
        $this->comment->setDI($this->di);
        $saved = $this->comment->save(array('id' => $this->id, 'content' => $this->Value('content'),  'name' => $this->Value('name'), 'pagekey' => $this->pagekey, 'updated' => $now, 'ip' => $this->di->request->getServer('REMOTE_ADDR')));
    
	//$this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else return false;
    }
    
    public function callbackDelete()
    {
    	$this->comment = new \dabu\Comments\Comments();
        $this->comment->setDI($this->di);
        
        $deleted = $this->comment->delete($this->id);
        
        if($deleted) 
        {
        return true;
        }
        else return false;
    	
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
        
    }
}
