<?php
class User {
  private $pdo;
  public function __construct($pdo) { $this->pdo = $pdo; }

  public function create($name, $email, $password, $role_id=3) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $this->pdo->prepare("INSERT INTO users (name,email,password,role_id) VALUES (?,?,?,?)");
    return $stmt->execute([$name,$email,$hash,$role_id]);
  }

  public function findByEmail($email) {
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    return $stmt->fetch();
  }

  public function all() {
    $stmt = $this->pdo->query("SELECT u.*, r.name AS role_name FROM users u JOIN roles r ON u.role_id=r.id");
    return $stmt->fetchAll();
  }
}
