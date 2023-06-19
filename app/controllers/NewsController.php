<?php
declare(strict_types=1);

namespace PSA\Controllers;


use Phalcon\Http\Response;
use Phalcon\Tag;
use PSA\Forms\NewsForm;
use PSA\Models\News;

class NewsController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setTemplateBefore('private');
        $this->tag->setTitle('News');

    }

    //view all the news
    public function indexAction()
    {
        $news = News::find();
        $this->view->news = $news;
    }

    //create some news
    public function addPostAction()
    {
        $forms = new NewsForm();


        if ($this->request->isPost()) {
            if ($forms->isValid($this->request->getPost()) == false) {
                foreach ($forms->getMessages() as $message) {
                    $this->flashSession->error((string)$message);
                }
            } else {

                $this->db->begin();

                $usersid = $this->auth->getUser()->id;


                $news = new News([
                    'new_header' => $this->request->getPost('new_header'),
                    'new_post' => $this->request->getPost('new_post'),
                    'usersID' => $usersid,
                ]);


                if ($news->create()) {
                    $this->db->commit();
                    $this->flashSession->success("A New Post Has Been Created Successfully.");
                    return $this->response->redirect('/news');
                } else {
                    $this->db->rollback();
                    foreach ($news->getMessages() as $message) {
                        $this->flashSession->error((string)$message);
                    }
                    return $this->response->redirect('/news');
                }


            }
        }
        $this->view->form = $forms;
    }
}