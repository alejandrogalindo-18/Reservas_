<?php
class ReservationController {
  private $model;
  public function __construct($pdo){ $this->model = new Reservation($pdo); }

  public function create(){
    $user = $_SESSION['user']['id'] ?? null;
    if(!$user){ $_SESSION['error']="Inicia sesión primero."; return; }
    $this->model->create($user, $_POST['destination'], $_POST['depart_date'], $_POST['return_date'], $_POST['seats'], $_POST['notes']);
    $_SESSION['success'] = "Reservación creada correctamente.";
  }
}
