<?php
declare(strict_types=1);

namespace PSA\Controllers;

use Phalcon\Http\Response;
use Phalcon\Tag;
use PSA\Helpers\Datatables;
use PSA\Models\Comments;
use PSA\Models\Departments;
use PSA\Models\TaskComments;
use PSA\Models\Tasks;
use DateInterval;


class TasksController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('private');
        $this->tag->setTitle('Tasks');
    }

    //all the tasks
    public function indexAction()
    {
        $tasks = Tasks::find([
            "order" => "created_at DESC",
        ]);

        $datatable = new Datatables;
        $this->view->css = $datatable->css();
        $js = $datatable->jsData();
        $js .= "<script type='text/javascript' language='javascript'>
        function deleteRole(id) {
            $.post('/roles/delete/' + id, function(data){
                $('#modal-delete').html(data);
            })
        }
        </script>";
        $this->view->js = $js;
        $this->view->breadcrumbs = "
        <li class='breadcrumb-item'><a href='/dashboard'><i class='fas fa-fw fa-tachometer-alt'></i> Dashboard</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-layer-group'></i> Roles</li>
        ";

        $this->view->task = $tasks;
    }

    // An action to manage the tasks
    public function manageAction($taskId)
    {
        $this->tag->setTitle("Tasks - " . $taskId);

        $departments = Departments::find();
        $tasks = Tasks::find();
        $comments = Comments::find();
        $task = Tasks::findFirstById($taskId);
        $commentsView = TaskComments::find([
            'conditions' => 'taskID = :taskId:',
            'bind' => [
                'taskId' => $taskId,
            ]
        ]);


        if (!$tasks || !$departments || !$comments) {
            $this->flash->error("User or Department or Comment was not found");
            return $this->dispatcher->forward([
                'action' => 'index'
            ]);
        }


        if ($task) {
            // Check if the task is active or closed
            if ($task->active == 1) {
                $this->view->answer = "Active";
            } else {
                $this->view->answer = "Closed";
            }

            $createdAt = date_create_from_format('Y-m-d H:i:s', $task->created_at);

            if ($createdAt !== false) {
                $departmentTasks = $task->departments_tasks;

                if ($departmentTasks === "no internet connection" || $departmentTasks === "internet issues") {
                    $createdAt->add(new DateInterval('PT30M'));
                }elseif($departmentTasks === "damaged" || $departmentTasks === "dismantling"){
                    $createdAt->add(new DateInterval('PT48H'));
                }

                $this->view->setVar('planned', $createdAt->format('Y-m-d H:i:s'));
            } else {
                echo "Invalid created_at Date Format";
                return;
            }
        } else {
            echo "Task Not Found";
            return;
        }

        if ($this->request->isPost()) {
            $this->db->begin();

            $departmentId = $this->request->getPost('department');
            $departmentTasks = $this->request->getPost('option_value');
            $createdComment = $this->request->getPost('comment');
            $id = $task->userID;


            if($departmentId && $departmentTasks) {


                $tasks = new Tasks([
                    'departments_tasks' => $departmentTasks,
                    'departmentID' => $departmentId,
                    'userID' => $id,
                    'task_creator' => $this->auth->getUser()->name,
                    'created_comment' => $createdComment,
                    'mainTaskID' => $task->mainTaskID ?: $taskId,
                    'lastTaskID' => $task->id,

                ]);


                if ($departmentId = 1 && $departmentTasks = 'no internet connection') {
                    $comments = new Comments([
                        'comment_type' => CallBack,
                        'comment' => 'no internet connection',
                        'name' => $this->auth->getUser()->name,
                        'user_id' => $id,
                    ]);
                } elseif ($departmentId = 1 && $departmentTasks = 'internet issues') {
                    $comments = new Comments([
                        'comment_type' => CallBack,
                        'comment' => 'internet speed issues',
                        'name' => $this->auth->getUser()->name,
                        'user_id' => $id,
                    ]);
                } else {
                    echo "<h1> something went wrong </h1>";
                    exit;
                }
                if ($task->active == 1) {
                    $task->active = 0;
                    $task->closed_at = date('Y-m-d H:i:s');
                    $task->task_closer = $this->auth->getUser()->name;
                    $task->closed_comment = "This Department Could Not Solve The Problem";
                    $task->save();

                    if ($tasks->create() && $comments->create()) {
                        $this->db->commit();
                        $this->flashSession->success("A New Task Has Been Created");
                        return $this->response->redirect('/tasks/manage/' . $taskId);
                    } else {
                        $this->db->rollback();
                        foreach ($tasks->getMessages() as $message) {
                            $this->flashSession->error((string)$message);
                        }
                        return $this->response->redirect('/tasks/manage/' . $taskId);
                    }
                } else {
                    $this->flashSession->error("This Task Is Not Active");
                }

            }elseif(!$departmentTasks && $departmentId === "nothing"){
                $taskComment = TaskComments::find();

                if(!$taskComment){
                    $this->flash->error("The DataBase For The Comments Of The Tasks Were Not Found");
                    return $this->dispatcher->forward([
                        'action' => 'index'
                    ]);
                }

                    $createdComment = $this->request->getPost('comment');

                $taskComments = new TaskComments([
                        'comment' => $createdComment,
                        'userID' => $this->auth->getUser()->id,
                        'taskID' => $taskId,
                    ]);

                if($task->active == 1) {

                    if ($taskComments->create()) {
                        $this->db->commit();
                        $this->flashSession->success("A New Comment Has Been Added On This Task");
                        return $this->response->redirect('/tasks/manage/' . $taskId);
                    } else {
                        $this->db->rollback();
                        foreach ($taskComments->getMessages() as $message) {
                            $this->flashSession->error((string)$message);
                        }
                        return $this->response->redirect('/tasks/manage/' . $taskId);
                    }
                }else{
                    $this->flashSession->error("This Task Is Not Active");
                }
            }else{

            }

        }

        //this calls a private function named GetAllPreviousTasks()
        //with that function I can get all the previous tasks created before the active one
        $relatedTasks = [];
        if ($task) {
            $this->getAllPreviousTasks($task, $relatedTasks);
        }

        $this->view->commentTasks = $commentsView;
        $this->view->relatedTasks = $relatedTasks;
        $this->view->department = $departments;
        $this->view->task = $task;
    }

    //  this function is used in manageAction
    //  with this function I can check all the related task that was created
    //  before this task
    private function getAllPreviousTasks($task, &$relatedTasks)
    {
        $lastTaskId = $task->lastTaskID;
        while ($lastTaskId) {
            $lastTask = Tasks::findFirstById($lastTaskId);
            if ($lastTask) {
                array_unshift($relatedTasks, $lastTask);
                $lastTaskId = $lastTask->lastTaskID;
            } else {
                break;
            }
        }
    }

    //this function bellow is used to check which department what tasks it has using ajax
    public function getTasksAction(){
        $department = $this->request->getQuery('department');
        $option_value = $this->request->getQuery('option_value');

        if ($department && $option_value) {
            $tasks = Task::find([
                'conditions' => [
                    'department = :department: AND option_value = :option_value:',
                    'bind' => [
                        'department' => $department,
                        'option_value' => $option_value
                    ]
                ]
            ]);
        } else {
            $tasks = [];
        }

        $this->view->tasks = $tasks;
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
        $this->view->disableLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        return $this->view->render('tasks', 'get-tasks');
    }




}
