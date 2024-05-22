<?php

class TaskModel {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=mysqldb;dbname=tareas;charset=utf8', 'root', 'tudai');
    }

    /**
     * Obtiene la lista de tareas dejando en primer lugar las que no fueron finalizadas.
     */
    public function getAll() {
        $query = $this->db->prepare('SELECT * FROM tareas ORDER BY finalizada ASC');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    
    /**
     * Retorna todas las tareas de acuerdo a la prioridad y si está finalizada o no.
     */
    public function getTasks($prioridad, $finalizada) {
        $query = $this->db->prepare("SELECT * FROM tareas WHERE prioridad = ? AND finalizada = ?");
        $query->execute(array($prioridad, $finalizada));
        return $query->fetchAll();
    }

    /**
     * Retorna una tarea según el id pasado.
     */
    public function get($idTarea) {
        $query = $this->db->prepare('SELECT * FROM tareas WHERE id_tarea = ?');
        $query->execute(array($idTarea));

        return $query->fetch(PDO::FETCH_OBJ);
    }


    /**
     * Guarda una tarea en la base de datos.
     */
    public function save($titulo, $descripcion, $prioridad) {

        $query = $this->db->prepare('INSERT INTO tareas(titulo, descripcion, prioridad, finalizada) VALUES(?,?,?,0)');
        $query->execute([$titulo, $descripcion, $prioridad]);

        return $this->db->lastInsertId();
    }

    private function uploadImage($image){
        $target = "img/task/" . uniqid() . "." . strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));  
        move_uploaded_file($image['tmp_name'], $target);
        return $target;
    }
 
    /**
     * Elimina una tarea de la BBDD según el id pasado.
     */
    function delete($idTarea) {
        $query = $this->db->prepare('DELETE FROM tareas WHERE id_tarea = ?');
        $query->execute([$idTarea]); 
    }

    /**
     * Actualiza la tarea y la marca finalizada.
     */
    function end($idTarea) {
        $query = $this->db->prepare('UPDATE tareas SET finalizada = 1 WHERE id_tarea = ?');
        $query->execute([$idTarea]);
    }

    function update($idTarea, $prioridad) {
        $query = $this->db->prepare('UPDATE tareas SET prioridad = ? WHERE id_tarea = ?');
        $query->execute([$prioridad, $idTarea]);
    }

    

}