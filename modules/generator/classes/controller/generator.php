<?php 
defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of generator
 *
 * @author burningface
 */
class Controller_Generator extends Kohana_Controller_Template {
    
    private static $SESSION_KEY = "generator_logged_in";
    private static $LOGIN_FAILD = "login faild!";
    
    
    public $template = "template";
    private $logged_in = false;
    private $links = array("assets", "model", "form", "controller", "logout");

    public function before() {
        parent::before();
         $this->logged_in = Session::instance()->get(self::$SESSION_KEY);
        if($this->request->action() != "login" && !$this->logged_in){
            Request::current()->redirect("generator/login");
        }
        $this->template->links = $this->links;
        $this->template->legend = ucfirst($this->request->action());        
    }
    
    public function action_index(){
        $config = Kohana::$config->load("generator");
        $this->template->content = "<h3>Hello ".ucwords(strtolower($config->get("author")))."!</h3><div>You are logged in!</div><div>Please setup your database first!</div>";
    }
    
    public function action_login(){
        $form = View::factory("forms/generatorlogin");
        $form->language = array("login" => "Login", "password" => "Password");
        $form->action = "generator/login";
        
        if(isset ($_POST["submit"])){
            $validation = Validation::factory($_POST)
                    ->rule("password", "not_empty");
            if($validation->check()){
                $config = Kohana::$config->load("generator");
                if($_POST["password"] == $config->get("password")){
                    Session::instance()->set(self::$SESSION_KEY, true);
                    $this->request->redirect("generator");
                }
            }else{
                $this->showFlash(self::$LOGIN_FAILD);
                $form->errors = $validation->errors("form_errors");
            }
        }
        
        $this->template->content = $form;
    }
    
    public function action_logout(){
        Session::instance()->delete(self::$SESSION_KEY);
        Request::current()->redirect("generator");
    }
    
    public function action_form(){
        $form = View::factory("forms/generatorform");
        $form->language = array("table" => "Db table names", 
            "form_table_button" => "Show", 
            "clear_button" => "Clear");
        $form->tablenames = Generator_Form::listTables(true);
        $this->template->content = $form;
    }
    
    public function action_loginform(){
        $this->template->content = "loginform";
    }
    
    public function action_model(){
        $form = View::factory("forms/generatormodel");
        $form->language = array("generate_model_button" => "Generate models",
            "clear_button" => "Clear",
            "date_format" => "Date format");
        $form->action = "generatorajax/model";
        $this->template->content = $form;
    }
    
    public function action_controller(){
        $form = View::factory("forms/generatorcontroller");
        $form->language = array("generate_controller_button" => "Generate controller", 
            "controller_name" => "Controller name",
            "add_action_button" => "Add new action method",
            "clear_button" => "Clear"
            );
        $form->action = "generatorajax/controller";
        $this->template->content = $form;
    }
    
    public function action_assets(){
        $form = View::factory("forms/generatorassets");
        $form->language = array("assets_button" => "Generate assets structure");
        $this->template->content = $form;
    }
    
    private function showFlash($flash){
        if(!empty($flash)){
            $this->template->flash = $flash;
        }
    }
    
}

?>
