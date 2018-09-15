# A simple Form Builder

[![Build Status](https://travis-ci.org/Hypario/FormBuilder.svg?branch=master)](https://travis-ci.org/Hypario/FormBuilder)

This Form Builder is a simple project for people who wants to start a project really fast with a Form without using any Framework such as Laravel or Symfony.
Forms support Bootstrap 4 and your own classes.

## Installation

```bash
composer require hypario/formbuilder
```

## How to use it ?

First you have to initialize the Form Builder.
```php
$form = new FormBuilder($_POST); // here we're using POST data, but it can be everything that are from a form
```

And then, create your form.The create function take an array as a parameter, the form need the method attribute and the action attribute.
```php
$form = new FormBuilder($_POST);
$form = $form->create(['action' => 'exemple.php', 'method' => 'post'])
```
By default, the form is created with no action and the method POST, it doesn't need any parameter.

After creating your form, you have to place your inputs
```php
$form = new FormBuilder($_POST);
$form = $form->create()->input('username')
```
The input takes 3 parameters, the name, the label and the attributes.
```php
$form = new FormBuilder($_POST);
$form = $form->create()
        ->input('username', 'Your username :', ['value' => 'Paul', 'class' => 'form-input'])
```
You can give any attributes you want if you're using you own attributes, by default the input have the name of the input, the value given by the datas, the id is the name and the class is the class given by the function setInputClass (see below)

You can also create a password field (instead of giving the password attribute to an input) and takes in parameters the same parameters as the input function.
```php
$form = new FormBuilder($_POST);
$form = $form->create()
        ->input('username', 'Your username :')
        ->password('password', 'Your password :')
```
