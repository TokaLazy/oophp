<?php

abstract class Controller
{
    public $request;

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function action($action)
    {
        if (method_exists($this, $action)) {
            $id = 0;

            if ($this->request->existParam('id')) {
                $id = $this->request->getParam('id');
            } elseif ($this->request->existParam('action')) {
                $id = $this->request->getParam('action');
            }

            $id = intval($id);

            if ($id) {
                $this->{$action}($id);
            } else {
                $this->{$action}();
            }
        } else {
            throw new Exception('Aucune méthode du nom de "'.$action.'" dans la class...');
        }
    }
}
