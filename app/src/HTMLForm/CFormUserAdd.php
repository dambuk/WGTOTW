<?php

namespace dabu\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUserAdd extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct([], [
            'acronym' => [
                'type'        => 'text',
                'label'       => 'Username',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'name' => [
                'type'        => 'text',
                'label'       => 'Name',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            
            
            'email' => [
                'type'        => 'text',
                'label'       => 'Email',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
            ],
            
            'password' => [
                'type'        => 'password',
                'label'       => 'Password',
                'required'    => true,
                'validation'  => ['not_empty', 'not_empty'],
            ],
            
            "passAgain" => [
            "type" => "password",
            "label" => "Repeat password",
            'required'    => true,
            "validation" => [
                "match" => "password", 'not_empty'
            ],
        ],
            
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Submit',
            ],
            
        ]);
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
        $active = !empty($_POST['active'])?$now:true;

	$this->newuser = new \dabu\Users\User();
        $this->newuser->setDI($this->di);
        
        $exists = $this->newuser->verifyAcronym($this->Value('acronym'));
        
        if (empty($exists)) {
        
        $saved = $this->newuser->save(array('acronym' => $this->Value('acronym'), 'email' => $this->Value('email'), 'name' => $this->Value('name'), 'password' => password_hash($this->Value('password'), PASSWORD_DEFAULT), 'created' => $now, 'updated' => $now, 'deleted' => null, 'active' => $active, 'gravatar' => 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->Value('email')))) . '.jpg'));
    
       // $this->saveInSession = true;
        
        if($saved) 
        {
        return true;
        }
        }
        
        elseif (!empty($exists)) {
        $this->AddOutput("<p><i>A user with same username already exists.</i></p>");
        return false;
        }
        else return false;
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
         $this->redirectTo('users/id/' . $this->newuser->getProperties()['id']);
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
