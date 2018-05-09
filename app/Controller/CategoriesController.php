<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 01/05/2018
 * Time: 15:36
 */

class CategoriesController
{
    public function create($parameters){
      return view('categories.create');
    }

    public function store($parameters){
      $validate = validator([
        'required' => ['name', 'description']
      ]);

      if (!$validate->check()) {
        redirect(route('categories', 'create'), ['errors' => $validate->getMessage()]);
        return;
      }

      $data = request()->all();
      $data['name'] = trim($data['name']);
      $data['slug'] = slug($data['name']);

      $categoryModel = new CategoryModel();

      $categoryModel->setData($data);
      $categoryModel->created_at = date('Y-m-d', time());
      $categoryModel->save();

      redirect('/', ['success' => ['Category created!!!!!!']]);
      return;
    }

}