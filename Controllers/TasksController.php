<?php
    namespace mvc\Controllers;

    use mvc\Core\Controller;
    use mvc\Models\TaskModel;
    use mvc\Models\TaskRepository;

    class TasksController extends Controller
    {
        private $taskRepository;

        public function __construct()
        {
            $this->taskRepository = new TaskRepository();
        }

        function index()
        {

            $tasks = new TaskModel();

            $d['tasks'] = $this->taskRepository->getAll($tasks);
            $this->set($d);
            $this->render("index");
        }

        public function create()
        {
            extract($_POST);
            if (isset($title) && !empty($title) && isset($description) && !empty($description)) {

                $task = new TaskModel();
                $task->setTitle($title);
                $task->setDescription($description);

                if ($this->taskRepository->add($task)) {
                    header("Location: " . WEBROOT . "tasks/index");
                }
            }

            $this->render("create");
        }

        function edit($id)
        {
            $task = new TaskModel();
            extract($_POST);
            $d['task'] = $this->taskRepository->get($id);
            if (isset($title)) {
                $task->setId($id);
                $task->setTitle($title);
                $task->setDescription($description);
                if ($this->taskRepository->edit($task)) {
                    header("Location: " . WEBROOT . "tasks/index");
                }
            }
            $this->set($d);
            $this->render("edit");
        }

        function delete($id)
        {
            $task = new TaskModel();
            $task->setId($id);
            if ($this->taskRepository->delete($task)) {

                header("Location: " . WEBROOT . "tasks/index");
            }
        }

    }

?>