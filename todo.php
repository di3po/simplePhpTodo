<?php
require 'header.html';
require 'form1.html';

try{
    $db = new PDO('pgsql:host=localhost;dbname=test', 'someuser', '123456');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$db->exec("DROP TABLE todos");
    /* $db->exec("CREATE TABLE todoDb (
        todo_id bigserial primary key,
        todo_name VARCHAR(255) NOT NULL,
        todo_status BOOLEAN NOT NULL DEFAULT FALSE
    )"); */
} catch (PDOException $e) {
    print $e->getMessage();
}

if($_SERVER['REQUEST_METHOD']=='POST') {
    if($_POST['submit-add']) {
        if(validate_form()) {
            add_todo();
        }
    }
    if($_POST['submit-delete']) {
        delete_todo();
        
    }
} else {
    show_list();
}

function validate_form() {
    if(strlen($_POST['newTask'])!=0) {
        return true;
    }
}

function add_todo() {
    try{
        $db = new PDO('pgsql:host=localhost;dbname=test', 'someuser', '123456');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    $stmt = $db->prepare("INSERT INTO tododb (todo_name, todo_status) VALUES (?, ?)");
    $stmt->execute(array($_POST['newTask'], 0));
    show_list();
}

function delete_todo() {
    try{
        $db = new PDO('pgsql:host=localhost;dbname=test', 'someuser', '123456');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    $q = $db->query("UPDATE tododb SET todo_status=true WHERE todo_id = '{$_POST['todo_id']}' ");
    $q = $db->query("DELETE FROM tododb WHERE todo_id='{$_POST['todo_id']}' ");
    print "LAST DELETED: {$_POST['todo_id']}-{$_POST['todo_name']}<br><br>";
    show_list();
}

function show_list() {
    try{
        $db = new PDO('pgsql:host=localhost;dbname=test', 'someuser', '123456');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    print "<strong>My list</strong><br><br>";
    $q = $db->query("SELECT * FROM tododb WHERE todo_status=false");
    while ($r = $q->fetch()) {
        $page = file_get_contents('form2.html');
        $page = str_replace('{value_todo_id}', $r['todo_id'], $page);
        $page = str_replace('{value_todo_name}', $r['todo_name'], $page);
        print $page;
    }
}

