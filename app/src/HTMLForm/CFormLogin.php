<?php

namespace dabu\HTMLForm;

/**
 * Form to add comment
 *
 */
class CFormLogin extends \Mos\HTMLForm\CForm
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
            
            'password' => [
                'type'        => 'password',
                'label'       => 'Password',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
          
            'submit' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
                'value'     => 'Log in',
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
    
    $this->users = new \dabu\Users\User();
    $this->users->setDI($this->di);
    
    $user = null;
    $acronym = $this->Value('acronym');
    
    $this->verifyacronym = $this->users->verifyAcronym($acronym);
    
    if ($this->verifyacronym != null) {
    
    $this->verified = $this->users->verifyPassword($acronym, $this->Value('password'));
    
    if ($this->verified) {
    
     $user = $this->verifyacronym[0];
     
     $deleted = $user->deleted;
     
     if (isset($deleted)) {
      $this->AddOutput("<p><i>This user has been deleted.<br>Contact page admin to log in again, or create new account.</i></p>");
      return false;
     
     }
    
     elseif(null !== $user->getProperties('acronym')) {
    
      $userdata = array();
      $userdata = $user->getProperties();
      $this->di->session->set('user', $userdata);

      return true;
      }
      else return false;
    }
    
    else {
    $this->AddOutput("<p><i>Wrong password</i></p>");
    return false;
    }
    
    }
    
    else {
    $this->AddOutput("<p><i>Wrong username</i></p>");
    return false;
    }
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
    //$this->AddOutput("<p><i>Du har loggats in.</i></p>");
      $this->redirectTo('user-login/show-login/success');
    }


    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        //$this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo('user-login/show-login/fail');
    }
}
