<?php
class QuizModel {
    private $db;

    function __construct($conn){
        $this->db = $conn;
    }

    function getAll(){
        return $this->db->query("SELECT * FROM quiz");
    }

    function getById($id){
        $req = $this->db->prepare("SELECT * FROM quiz WHERE id_quiz=?");
        $req->execute([$id]);
        return $req->fetch();
    }

    function add($titre, $desc, $diff){
        $req = $this->db->prepare("INSERT INTO quiz (titre, description, difficulte) VALUES (?,?,?)");
        return $req->execute([$titre, $desc, $diff]);
    }

    function update($id, $titre, $desc, $diff){
        $req = $this->db->prepare("UPDATE quiz SET titre=?, description=?, difficulte=? WHERE id_quiz=?");
        return $req->execute([$titre, $desc, $diff, $id]);
    }

    function delete($id){
        $req = $this->db->prepare("DELETE FROM quiz WHERE id_quiz=?");
        return $req->execute([$id]);
    }
}
?>
