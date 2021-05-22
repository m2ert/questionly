<?php
function setTitleFromRoute($route){
    switch ($route) {
        case '':
        $title = "Questionly - A place to share knowledge and better understand the world";
            break;
        case 'spaces':
        $title = "Questionly - Spaces";
            break;
        case 'answers':
        $title = "Questionly - Answers";
            break;
        case 'answer-detail':
            $title = "Questionly - Question&Answers";
                break;
        case 'questions':
        $title = "Questionly - Questions";
            break;
        case 'profile':
        $title = "Questionly - Profile";
            break;
        default:
        $title = "Questionly - A place to share knowledge and better understand the world";
            break;
    }
    return $title;
}

function getAllResources($route){
    switch ($route) {
        case '':
            return array ('css' => array('<link rel="stylesheet" href="/css/login.css" />','<link rel="stylesheet" href="/css/sweetalert.css" />'),'js' => array('<script src="/js/jquery.js"></script>','<script src="/js/validation.js"></script>','<script src="/js/sweetalert.js"></script>','<script src="/js/login.js"></script>'));
            break;
        case 'spaces':
            return array ('css' => array('<link rel="stylesheet" href="/css/index.css" />','<link rel="stylesheet" href="/css/sweetalert.css" />','<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />'),'js' => array('<script src="/js/jquery.js"></script>','<script src="/js/index.js"></script>','<script src="/js/sweetalert.js"></script>'));
            break;
        case 'answers':
            return array ('css' => array('<link rel="stylesheet" href="/css/index.css" />','<link rel="stylesheet" href="/css/sweetalert.css" />','<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />'),'js' => array('<script src="/js/jquery.js"></script>','<script src="/js/index.js"></script>','<script src="/js/sweetalert.js"></script>'));
            break;
        case 'answer-detail':
            return array ('css' => array('<link rel="stylesheet" href="/css/index.css" />','<link rel="stylesheet" href="/css/profile.css" />','<link rel="stylesheet" href="/css/sweetalert.css" />','<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />'),'js' => array('<script src="/js/jquery.js"></script>','<script src="/js/index.js"></script>','<script src="/js/sweetalert.js"></script>'));
            break;
        case 'questions':
            return array ('css' => array('<link rel="stylesheet" href="/css/index.css" />','<link rel="stylesheet" href="/css/sweetalert.css" />','<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />'),'js' => array('<script src="/js/jquery.js"></script>','<script src="/js/index.js"></script>','<script src="/js/sweetalert.js"></script>'));
            break;
        case 'profile':
            return array ('css' => array('<link rel="stylesheet" href="/css/index.css" />','<link rel="stylesheet" href="/css/sweetalert.css" />','<link rel="stylesheet" href="/css/profile.css" />','<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />'),'js' => array('<script src="/js/jquery.js"></script>','<script src="/js/index.js"></script>','<script src="/js/sweetalert.js"></script>'));
            break;
        default:
        return array ('login.css','login.js');
            break;
    }
}
