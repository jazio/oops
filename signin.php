<?php
/**
 * Sign In
 */
require_once 'config/config.php';

use \lib\User;
use \lib\FormValidator;
use \lib\Connector;

if (isset($_POST['submit'])) {
    // Prepare the dependency injection.
    $field = new FormValidator();
    $db = new Connector();
    $username = $_POST['username'];
    $password = $_POST['password'];
            if ($field->isValid($username,'text') && $field->isValid($password,'password')) {

                try {
                    $user = new User($db);
                    $q = $user->login($username, $password);
                    if ($q == 1) {
                        session_start();
                        $_SESSION['login'] = 1;
                        echo $twig->render('home.twig', array(
                            'user' => $username,
                            'message' => "Dear {$username} welcome inside.",
                        ));
                    }
                }
                catch (Exception $e) {
                    //$e->getMessage()
                    echo $twig->render('signin.twig', array('message' => 'Username and password not valid.'));
                    //throw new \Exception('Cannot login.' . $e->getMessage() . '<br/>' . $field->err);
                }


            }
            else {
                echo $twig->render('signin.twig', array('message' => 'Fill in the empty fields'));
            }

    } else {
        echo $twig->render('signin.twig');
    }
