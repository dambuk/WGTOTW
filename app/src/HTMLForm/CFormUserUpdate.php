<?php

namespace dabu\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUserUpdate extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    private $user;
    private $id;
    private $acronym; 
    private $activedate;
    
    /**
     * Constructor
     *
     */
    public function __construct($id=null, $acronym='',$name='',$email='', $activedate=null)
    {
	$activecheck = ($activedate == null) ? false : true;
	

     parent::__construct([], [
            
            'name' => [
                'type'        => 'text',
                'label'       => 'Name',
                'required'    => true,
                'validation'  => ['not_empty'],
                'value'       => $name,
            ],
            'email' => [
                'type'        => 'text',
                'label'       => 'Email',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                'value'       => $email,
            ],  
            
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Submit',
            ],
            'submit-delete' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmitDelete'],
                'value'     => 'Delete',
     ],
        ]);
        
        $this->id = $id;
        $this->acronym = $acronym;
        $this->activedate = $activedate;
        
        
        
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
    	

    	
        $now = gmdate('Y-m-d H:i:s');
        
        if ($this->activedate == null && !empty($_POST['active'])) {
	    $this->activedate = $now;
        }
        else if ($this->activedate != null && empty($_POST['active'])) {
	    $this->activedate = null;
        }

	   $this->user = new \dabu\Users\User();
        $this->user->setDI($this->di);
        $saved = $this->user->save(array('id' => $this->id, 'acronym' => $this->acronym, 'email' => $this->Value('email'), 'name' => $this->Value('name'), 'password' => 'test', 'updated' => $now, 'deleted' => null, 'active' => $this->activedate, 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->Value('email')))) . '.jpg'));
    
       // $this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        else return false;
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitDelete()
    {
	$users = new \dabu\Users\User();
	$users->setDI($this->di);
        
    
        if ($this->acronym == 'admin') {
    		$this->AddOutput("<p><i>Admin can't be deleted</i></p>");
        return false;
    	}
    	
    	elseif ( $users->getLoggedInUser() != 'admin') {
    		$this->AddOutput("<p><i>Only page admin can delete a user</i></p>");
       return false;
    	}
        else {
        $this->redirectTo('users/soft-delete/' . $this->id);
        
        }
        
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
         $this->redirectTo('users/id/' . $this->user->getProperties()['id']);
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        //$this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo();
    }
}
