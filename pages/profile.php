<?php 
include 'shared/header.php'; 
include 'shared/navbar.php'; 


define("db_server", "localhost");
define("db_user", "question_admin");
define("db_password", "**********");
define("db", "question_project");

try {
	$db = new PDO('mysql:host=' . db_server . ';charset=utf8;dbname=' . db, db_user, db_password);
} catch (PDOexception $e) {
	echo $e->getMessage();
	exit;
}

$user_detail = $db->query("SELECT user_desc,user_img FROM user_profile WHERE user_id='{$_SESSION['user_id']}'")->fetch(PDO::FETCH_ASSOC);

?>

<main class="profile-center">
	<div class="profile-user">
		<div class="profile-image">
			<img src="/images/user-img/<?=$user_detail['user_img']?>" alt="profile-img" class="profile-img" />
			<h1 id="profile-name"><?= $_SESSION['user_name'] ?></h1>
		</div>
		<div class="profile-description">
			<p id="description">
				<?=$user_detail['user_desc'] ? $user_detail['user_desc']."<br><br><a style='cursor:pointer;' id='edit-profile' class='active'>Edit about me</a>" : "<a style='cursor:pointer;' id='edit-profile' class='active'>Edit about me</a>"?>
			</p>
		</div>
		<br>
	</div>
	<div class="profile-bar">
		<div class="answers active">
			<span id="p-answer-count"></span>
			<a data-href="answer-post" id="answer">Answers</a>
		</div>
		<div class="questions">
			<span id="p-question-count"></span>
			<a data-href="question-post" id="question">Questions</a>
		</div>
	</div>
	<?php
	$allAnswers = $db->query("SELECT a.answer,a.answer_date,qr.user_name,a.answer_id,a.question_id,a.answerer_id FROM answers a INNER JOIN question_register qr ON qr.user_id = a.answerer_id WHERE a.answerer_id = '{$_SESSION['user_id']}' ORDER BY a.answer_date DESC", PDO::FETCH_ASSOC);
	if ($allAnswers->rowCount()) {
		foreach ($allAnswers as $answer) {
			$vote_count = $db->query("SELECT COUNT(vote_id) AS total FROM answer_vote WHERE answer_id = '{$answer['answer_id']}'")->fetch(PDO::FETCH_ASSOC);
			$question_detail = $db->query("SELECT q.question_id,q.question,sp.space_name FROM questions q INNER JOIN spaces sp ON sp.space_id = q.question_category WHERE questioner_id='{$answer['question_id']}'")->fetch(PDO::FETCH_ASSOC);
			$user_profile = $db->query("SELECT user_img FROM user_profile WHERE user_id = '{$answer['answerer_id']}'")->fetch(PDO::FETCH_ASSOC);

	?>
	<article class="answer-post">
		<div class="identifier">
			<small id="type">Answer</small> -
			<small id="space-title"><?=$question_detail['space_name']?></small>
		</div>
		<div class="user-answer">
			<div class="user-img">
				<img class="post-user-img" src="/images/user-img/<?=$user_profile['user_img']?>" alt="user-profile-img" />
			</div>
			<div class="user-credentials">
				<h4 class="user-name"><?=$answer['user_name'];?></h4>
				<p class="user-description">
					<?= date("d-m-Y H:i", strtotime($answer['answer_date'])); ?>
				</p>
			</div>
		</div>
		<div class="user-question-title">
			<a href="single-answer.html" target="_blank"><?=$question_detail['question'];?></a>
		</div>
		<div class="user-question-answer">
			<?=$answer['answer'];?>
		</div>
		<footer class="answer-cta">
			<div class="vote-container">
				<button class="upvote"><i class="fa fa-thumbs-up"></i></button>
				<span id="vote-count"><?= $vote_count['total'] ? $vote_count['total'] : 0; ?></span>
			</div>
		</footer>
	</article>
	<?php
		}
	} else {
		echo '<article class="answer-post">
		<div class="user-question-title">
					There is no answer.
				</div>
		</article';
	}
	?>
	<!-- answer template -->
	<?php
	$myQuestions = $db->query("SELECT q.question_id,q.question,sp.space_name FROM questions q INNER JOIN spaces sp ON sp.space_id = q.question_category WHERE questioner_id='{$_SESSION['user_id']}'", PDO::FETCH_ASSOC);
	if ($myQuestions->rowCount()) {
		foreach ($myQuestions as $question) {
			
	?>
			<article class="question-post" style="display:none;">
				<div class="identifier">
					<small id="type">Question added</small> -
					<small id="space-title"><?=$question['space_name']?></small>
				</div>
				<div class="user-question-title">
					<a href="question-answers/<?=$question['question_id']?>" target="_blank"><?=$question['question']?></a>
				</div>
			</article>
	<?php
		}
	} else {
		echo '<article class="question-post" style="display:none;">
		<div class="user-question-title">
					There is no question.
				</div>
		</article';
	}
	?>
	<!-- question template -->
</main>

<?php include 'shared/footer.php'; ?>
<script>
	$(document).ready(function() {
		$('#answer').click(function(e) {
			$('.answers').addClass('active');
			$('.questions').removeClass('active');
			$('.answer-post').css("display", "block");
			$('.question-post').css("display", "none");
		});
		$('#question').click(function(e) {
			$('.questions').addClass('active');
			$('.answers').removeClass('active');
			$('.question-post').css("display", "block");
			$('.answer-post').css("display", "none");
		});
		$('#edit-profile').click(function () { 
			let user = $(this).data("uid");
			Swal.fire({
                title: 'About Me',
                input: 'textarea',
                inputAttributes: {
                    autocapitalize: 'on'
                },
                showCancelButton: true,
                confirmButtonText: 'Add',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/action/edit-description",
                        data: "user_id=<?= $_SESSION['user_id'] ?>&description=" + result.value,
                        success: function(r) {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            Toast.fire({
                                icon: r.outcome ? 'success' : 'error',
                                title: r.message,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            }).then((result) => {
                                location.reload();
                            })
                        }
                    });
                }
            })
		});
	});
</script>