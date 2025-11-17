<?php
require_once "./model/QuizModel.php";

class QuizController {
    private $model;

    function __construct($conn){
        $this->model = new QuizModel($conn);
    }

    function list(){
        $data = $this->model->getAll();
        include "./view/back/quiz_list_admin.php";
    }

    function addForm(){
        include "./view/back/quiz_add.php";
    }

    function add(){
        if(empty($_POST["titre"]) || empty($_POST["description"]) || empty($_POST["difficulte"])){
            $error = "Tous les champs sont obligatoires";
            include "./view/back/quiz_add.php";
            return;
        }
        $this->model->add($_POST["titre"], $_POST["description"], $_POST["difficulte"]);
        header("Location:index.php?action=listQuiz");
    }

    function delete($id){
        $this->model->delete($id);
        header("Location:index.php?action=listQuiz");
    }
}
?>
