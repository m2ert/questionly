<?php
ob_start();
session_start();
if (!$_SESSION['user_id']) {
  header('Location: /');
  exit;
}
?>
<nav>
    <div class="nav-container">
        <a class="logo" href="/spaces">
            <h2>Questionly</h2>
        </a>
        <div class="nav-links">
            <span title="Home">
                <a class="fas fa-home <?=$_GET['route'] == 'answers' ? 'active' : ''?>" href="/answers/"> </a>
            </span>
            <span title="Spaces">
                <a class="fas fa-users <?=$_GET['route'] == 'spaces' ? 'active' : ''?>" href="/spaces"></a>
            </span>
            <span title="Profile">
                <a class="fas fa-user user <?=$_GET['route'] == 'profile' ? 'active' : ''?>" href="/my-profile"></a>
            </span>
            <span title="Log Out">
                <a class="fa fa-power-off" href="/log-out"></a>
            </span>
        </div>
    </div>
</nav>