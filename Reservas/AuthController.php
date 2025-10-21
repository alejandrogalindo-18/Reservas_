<?php
class AuthController {
  private $userModel;
  public function __construct($pdo){ $this->userModel = new User($pdo); }

  public function register(){
    $name = $_POST['name']; $email = $_POST['email']; $pass = $_POST['password'];
    if($this->userModel->findByEmail($email)){
      $_SESSION['error'] = "Email ya registrado.";
      return;
    }
    $this->userModel->create($name,$email,$pass);
    $_SESSION['success'] = "Registro exitoso, inicia sesión.";
  }

  public function login(){
    $email = $_POST['email']; $pass = $_POST['password'];
    $user = $this->userModel->findByEmail($email);
    if(!$user || !password_verify($pass, $user['password'])){
      $_SESSION['error'] = "Credenciales inválidas.";
      return;
    }
    $_SESSION['user'] = $user;
  }

  public function logout(){
    session_destroy(); session_start();
  }
}
