<?php

header('Content-Type: application/json');
date_default_timezone_set('Europe/Istanbul');

define("db_server", "localhost");
define("db_user", "question_admin");
define("db_password", "**********");
define("db", "question_project");

$action = $_GET['action'];

if($action == "signup"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    $name = $_POST['signup_name'];
    $email = $_POST['signup_email'];
    $password = $_POST['signup_password'];


    $user = $db->query("SELECT user_id FROM question_register WHERE user_email = '{$email}'")->fetch(PDO::FETCH_ASSOC);
	if($user['user_id'] > 0){
		$result = array(
            "outcome" => false,
            "message" => "An account was created with this e-mail address previously."
        );
	}else{
        $query = $db->prepare("INSERT INTO question_register SET
        user_name = ?,
        user_email = ?,
        user_password = ?");
        $insertUser = $query->execute(array(
            $name, $email, md5($password)
        ));
        if($insertUser){

            $last_id = $db->lastInsertId();
            $profile_query = $db->prepare("INSERT INTO user_profile SET
            user_id = ?,
            user_desc = ?,
            user_img = ?");
            $insert_profile = $profile_query->execute(array(
                $last_id, "Hello world!", "profile-img.jpg"
            ));

            $result = array(
                "outcome" => true,
                "message" => "Your registration has been successfully taken."
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Try again later."
            );
        }
    }

    echo json_encode($result);
    
}
if($action == "login"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    $email = $_POST['login_email'];
    $password = $_POST['login_password'];


    $user = $db->query("SELECT user_id,user_password,user_name FROM question_register WHERE user_email = '{$email}'")->fetch(PDO::FETCH_ASSOC);
	if($user['user_id'] > 0){
		if($user['user_password'] == md5($password)){

            ob_start();
            session_start();
            $_SESSION["user_id"] = $user['user_id'];
            $_SESSION["user_name"] = $user['user_name'];

            $result = array(
                "outcome" => true,
                "message" => "Welcome, you're being redirect",
                'link' => "/spaces"
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Your password is incorrect",
                'link' => "/"
            );
        }
	}else{
      
        $result = array(
            "outcome" => false,
            "message" => "No account found.",
            'link' => "/"
        );
        
    }

    echo json_encode($result);
    
}
if($action == "unfollow"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    $user_id = $_POST['user_id'];
    $cat_id = $_POST['category_id'];


    $followRecord = $db->query("SELECT id FROM spaces_followers WHERE space_id = '{$cat_id}' AND follower_id = '{$user_id}'")->fetch(PDO::FETCH_ASSOC);
	if($followRecord['id'] > 0){
		$query = $db->prepare("DELETE FROM spaces_followers WHERE id = :id");
        $delete = $query->execute(array(
        'id' => $followRecord['id']
        ));

        $result = array(
            "outcome" => true,
            "message" => "Unfollow successfully.",
        );

	}else{
      
        $result = array(
            "outcome" => false,
            "message" => "No follow record found.",
        );
        
    }

    echo json_encode($result);
    
}
if($action == "follow"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    $user_id = $_POST['user_id'];
    $cat_id = $_POST['category_id'];


    $followRecord = $db->query("SELECT id FROM spaces_followers WHERE space_id = '{$cat_id}' AND follower_id = '{$user_id}'")->fetch(PDO::FETCH_ASSOC);
	if($followRecord['id'] > 0){
        $result = array(
            "outcome" => false,
            "message" => "Already followed.",
        );

	}else{
      
        $query = $db->prepare("INSERT INTO spaces_followers SET
        space_id = ?,
        follower_id = ?");
        $insert = $query->execute(array(
            $cat_id, $user_id
        ));
        if ($insert){
            $last_id = $db->lastInsertId();
            $result = array(
                "outcome" => true,
                "message" => "Follow successfully.",
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Try again later.",
            );
        }
    }

    echo json_encode($result);
    
}
if($action == "create-space"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    
    $user_id = $_POST['user_id'];
    $space_name = strtoupper($_POST['space_name']);


    $space = $db->query("SELECT space_id FROM spaces WHERE space_name = '{$space_name}'")->fetch(PDO::FETCH_ASSOC);
	if($space['space_id'] > 0){
		$result = array(
            "outcome" => false,
            "message" => "A space was created with this name previously."
        );
	}else{
        $query = $db->prepare("INSERT INTO spaces SET
        space_name = ?,
        creator_id = ?");
        $insertSpace = $query->execute(array(
            $space_name, $user_id
        ));
        if($insertSpace){
            $result = array(
                "outcome" => true,
                "message" => "Your space has been successfully create."
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Try again later."
            );
        }
    }

    echo json_encode($result);
    
}
if($action == "ask-a-question"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    
    $user_id = $_POST['user_id'];
    $question = $_POST['question'];
    $category_id = $_POST['category_id'];


        $query = $db->prepare("INSERT INTO questions SET
        question = ?,
        questioner_id = ?,
        question_category = ?");
        $insert_question = $query->execute(array(
            $question, $user_id, $category_id
        ));
        if($insert_question){
            $result = array(
                "outcome" => true,
                "message" => "Your question has been successfully create."
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Try again later."
            );
        }


    echo json_encode($result);
    
}
if($action == "answer-question"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    
    $user_id = $_POST['user_id'];
    $answer = $_POST['answer'];
    $category_id = $_POST['category_id'];
    $question_id = $_POST['question_id'];


        $query = $db->prepare("INSERT INTO answers SET
        question_id = ?,
        answerer_id = ?,
        answer = ?,
        answer_category = ?");
        $insert_question = $query->execute(array(
            $question_id, $user_id, $answer, $category_id
        ));
        if($insert_question){
            $result = array(
                "outcome" => true,
                "message" => "Your answer has been successfully create."
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Try again later."
            );
        }


    echo json_encode($result);
    
}
if($action == "vote"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    
    $user_id = $_POST['user_id'];
    $answer_id = $_POST['answer_id'];
    $type = $_POST['type'];

    $vote = $db->query("SELECT vote_id FROM answer_vote WHERE answer_id = '{$answer_id}' AND  voter_id = '{$user_id}'")->fetch(PDO::FETCH_ASSOC);

    if($type == "up"){
        if($vote['vote_id'] > 0){
            $result = array(
                "outcome" => false,
                "message" => "Voted previously."
            );
        }else{
            $query = $db->prepare("INSERT INTO answer_vote SET
            answer_id = ?,
            voter_id = ?");
            $insertvote = $query->execute(array(
                $answer_id, $user_id
            ));
            if($insertvote){
                $result = array(
                    "outcome" => true,
                    "message" => "Voted successfully."
                );
            }else{
                $result = array(
                    "outcome" => false,
                    "message" => "Try again later."
                );
            }
        }
    }else{
        $query = $db->prepare("DELETE FROM answer_vote WHERE vote_id = :vote_id");
        $delete = $query->execute(array(
        'vote_id' => $vote['vote_id']
        ));

        $result = array(
            "outcome" => true,
            "message" => "Unvoted successfully.",
        );
    }


    echo json_encode($result);
    
}
if($action == "edit-description"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    
    $user_id = $_POST['user_id'];
    $user_description = $_POST['description'];

    $description = $db->query("SELECT user_id FROM question_register WHERE user_id = '{$user_id}'")->fetch(PDO::FETCH_ASSOC);

    if($description['user_id'] > 0){
        $query = $db->prepare("UPDATE user_profile SET
        user_desc = :user_desc
        WHERE user_id = :user_id");
        $update = $query->execute(array(
            "user_desc" => $user_description,
            "user_id" => $user_id
        ));
        if ( $update ){
            $result = array(
                "outcome" => true,
                "message" => "Updated successfully."
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Try again later."
            );
        }
    }else{
        $result = array(
            "outcome" => false,
            "message" => "Try again later."
        );
    }  


    echo json_encode($result);
    
}
if($action == "comment-answer"){

    try {
        $db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
    } catch (PDOexception $e) {
       echo $e->getMessage();
       exit;
    }

    
    $user_id = $_POST['user_id'];
    $comment = $_POST['comment'];
    $answer_id = $_POST['answer_id'];


        $query = $db->prepare("INSERT INTO answer_comment SET
        answer_id = ?,
        commentor_id = ?,
        comment = ?");
        $insert_comment = $query->execute(array(
            $answer_id, $user_id, $comment
        ));
        if($insert_comment){
            $result = array(
                "outcome" => true,
                "message" => "Your comment has been successfully create."
            );
        }else{
            $result = array(
                "outcome" => false,
                "message" => "Try again later."
            );
        }


    echo json_encode($result);
    
}