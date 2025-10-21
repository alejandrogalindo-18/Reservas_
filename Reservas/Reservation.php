<?php
class Reservation {
  private $pdo;
  public function __construct($pdo){ $this->pdo = $pdo; }

  public function all(){
    $stmt = $this->pdo->query("SELECT r.*, u.name AS user_name FROM reservations r JOIN users u ON r.user_id=u.id ORDER BY r.id DESC");
    return $stmt->fetchAll();
  }

  public function create($user_id, $destination, $depart_date, $return_date, $seats, $notes=''){
    $stmt = $this->pdo->prepare("INSERT INTO reservations (user_id,destination,depart_date,return_date,seats,notes) VALUES (?,?,?,?,?,?)");
    return $stmt->execute([$user_id,$destination,$depart_date,$return_date,$seats,$notes]);
  }
}
