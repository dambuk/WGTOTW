<?php
namespace dabu\Users;

class UserLoginController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

  private $loggedin;

  
  /**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->users = new \dabu\Users\User();
    $this->users->setDI($this->di);

}
  
  
  public function ShowLoginAction($message=null) {
  
    $this->loggedin = $this->users->isLoggedIn();
    
    $message = $this->getMessage($message);
  
    if(!$this->loggedin) {
    
    
    
     $form = new \dabu\HTMLForm\CFormLogin();
     $form->setDI($this->di);
     $form->check();
      $this->theme->setTitle("Login");
       $this->views->add('users/no-account', [
      ], 'flash');
      $this->views->add('me/page', [
        'content' => $message,
      ], 'flash');
     

      $this->views->add('default/page', [
        'content' => '<div class="login-form">'.$form->getHTML().'</div>',
        'title' => "Login",
      ], 'main');
      
    }
    if($this->loggedin){
    
    $this->theme->setTitle("Logout");
    
    $this->views->add('me/page', [
        'content' => $message,
      ], 'flash');

      $this->views->add('default/page', [
        'content' => '<a id="logout" href="'.$this->di->get('url')->create('user-login/logout').'">Logout</a>',
        'title' => "",
      ], 'flash');
    }
  
  }
  
  public function getMessage($message=null) {
  
  switch ($message) {
  
  case 'success':
  
  $message = "You are logged in.";
  
  break;
  
  case 'fail':
  
  $message = "You are not logged in.";
  
  break;
  
  case 'out':
  
  $message = "You are logged out.";
  
  break;
  
  default: 
  
  $message = null;
  
  }
  
  return $message;
  
  }
  
  

  public function LoginAction($acronym, $password) {
  
  $verified = $this->users->verify($acronym, $password);
    
  if($verified) {
    
    $_SESSION['user']->acronym = $acronym;
    $_SESSION['user']->name = $verified->name;
    $_SESSION['user']->id = $verified->id;
    $this->loggedin = true;
    

  }
  
  
  
  }
  

  
  
  public function LogoutAction() {
    unset($_SESSION['user']);
    $this->loggedin = false;
    
    $url = $this->url->create('user-login/show-login/out');
    $this->response->redirect($url);

    
  }


}


