<?php
require_once('models/TaskModel.php');
require_once('views/JSONView.php');

class TaskApiController {

    private $model;
    private $view;

    private $data;

    public function __construct() {
        $this->model = new TaskModel();
        $this->view = new JSONView();

        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function obtenerTareas() {

        $tareas = $this->model->getAll();
        $this->view->response($tareas, 200);

    }

    public function obtenerTarea($params = null) {
        $id = $params[':ID'];

        $tarea = $this->model->get($id);
        $this->view->response($tarea, 200);

    }  
    
    public function addTarea() {
        $tareaNueva = $this->getData();

        $lastId = $this->model->save(
                $tareaNueva->titulo, 
                $tareaNueva->descripcion, 
                $tareaNueva->prioridad);

        $this->view->response("Se insertó correctamente con id: $lastId", 200);

    }

    public function borrarTarea($params = null) {
        $id = $params[':ID'];
        $tarea = $this->model->get($id);
        if ($tarea) {
            $this->model->delete($id);

            $this->view->response("Tarea $id, eliminada", 200);
        } else {
            $this->view->response("Tarea $id, no encontrada", 404);
        }
    }

    public function finalizaTarea($params = null) {
        $id = $params[':ID'];
        $tarea = $this->model->get($id);
        if ($tarea) {
            $titulo = $tarea->titulo;
            $this->model->end($id);

            $this->view->response("Tarea $titulo, finalizada", 200);
        } else {
            $this->view->response("Tarea $id, no encontrada", 404);
        }
    }    
}