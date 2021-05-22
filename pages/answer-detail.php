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
$question_detail = $db->query("SELECT question,question_category FROM questions WHERE question_id = '{$_GET['question_id']}'")->fetch(PDO::FETCH_ASSOC);
$allAnswers = $db->query("SELECT a.answer,a.answer_date,qr.user_name,a.answer_id,a.answerer_id FROM answers a INNER JOIN question_register qr ON qr.user_id = a.answerer_id WHERE a.question_id = '{$_GET['question_id']}' ORDER BY a.answer_date DESC", PDO::FETCH_ASSOC);

?>
<main class="single-answer-center">
	<h2 id="answer-title">
		<?= $question_detail['question']; ?>
	</h2>
	<article class="question-post single-answer">
		<footer class="answer-cta">
			<div class="comment-container">
				<button id="comment"><i class="fas fa-pencil-alt"></i></i></button>
				<span id="comment-count">Answer</span>
			</div>
		</footer>
	</article>
	<div class="add-comment-field invisible">
		<img class="post-user-img" src="/images/user-img/<?= $user_detail['user_img'] ?>" alt="user-profile-img" />
		<input type="text" id="answer-text" name="answer-text" placeholder="Answer this question" />
		<button data-qid="<?= $_GET['question_id'] ?>" id="add-answer-btn" class="add-comment-btn">Send</button>
	</div>
	<div class="single-answer-bar">
		<p id="single-answer-count"><?= $allAnswers->rowCount() ?> Answers</p>
	</div>
	<?php
	if ($allAnswers) {
		foreach ($allAnswers as $answer) {
			$vote_count = $db->query("SELECT COUNT(vote_id) AS total FROM answer_vote WHERE answer_id = '{$answer['answer_id']}'")->fetch(PDO::FETCH_ASSOC);
			$user_profile = $db->query("SELECT user_img FROM user_profile WHERE user_id = '{$answer['answerer_id']}'")->fetch(PDO::FETCH_ASSOC);
			$comment_count = $db->query("SELECT COUNT(comment_id) AS total FROM answer_comment WHERE answer_id = '{$answer['answer_id']}'")->fetch(PDO::FETCH_ASSOC);
	?>
			<article class="answer-post single-answer">
				<div class="user-answer">
					<div class="user-img">
						<img class="post-user-img" src="/images/user-img/<?= $user_profile['user_img'] ?>" alt="user-profile-img" />
					</div>
					<div class="user-credentials">
						<h4 class="user-name"><?= $answer['user_name']; ?></h4>
						<p class="user-description">
							<?= date("d-m-Y H:i", strtotime($answer['answer_date'])); ?>
						</p>
					</div>
				</div>
				<div class="user-question-answer">
					<?= $answer['answer']; ?>
				</div>
				<footer class="answer-cta">
					<div class="vote-container">
						<button data-aid="<?= $answer['answer_id']; ?>" class="upvote"><i class="fas fa-chevron-up"></i></button>
						<span id="vote-count"><?= $vote_count['total'] ? $vote_count['total'] : 0; ?></span>
						<button data-aid="<?= $answer['answer_id']; ?>" class="downvote">
							<i class="fas fa-chevron-down"></i>
						</button>
					</div>
					<div class="comment-container">
						<button id="comment"><i class="far fa-comment"></i></button>
						<span id="comment-count"><?= $comment_count['total'] ? $comment_count['total'] : 0; ?></span>
					</div>
				</footer>
			</article>
			<div class="add-comment-field invisible">
				<img class="post-user-img" src="/images/user-img/<?= $user_profile['user_img'] ?>" alt="user-profile-img">
				<input type="text" id="comment-text" placeholder="Add a comment" />
				<button data-aid="<?= $answer['answer_id']; ?>" id="add-comment-btn" class="add-comment-btn">
					Send comment
				</button>
			</div>
	<?php
		}
	}
	?>
</main>

<?php include 'shared/footer.php'; ?>
<script>
	$(function() {
		$('#add-comment-btn').click(function(e) {
			let comment = $('#comment-text').val();
			let aid = $(this).data("aid");

			$.ajax({
				type: "POST",
				url: "/action/comment-answer",
				data: "comment=" + comment + "&answer_id=" + aid + "&user_id=<?= $_SESSION['user_id'] ?>",
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
		});
		$('#add-answer-btn').click(function(e) {
			let answer = $('#answer-text').val();
			let qid = $(this).data("qid");

			$.ajax({
				type: "POST",
				url: "/action/answer-question",
				data: "answer=" + answer + "&question_id=" + qid + "&category_id=<?= $question_detail['question_category'] ?>&user_id=<?= $_SESSION['user_id'] ?>",
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
		});
		$('.upvote').click(function(e) {
			let aid = $(this).data("aid");
			$.ajax({
				type: "POST",
				url: "/action/vote",
				data: "answer_id=" + aid + "&user_id=<?= $_SESSION['user_id'] ?>&type=up",
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
		});
		$('.downvote').click(function(e) {
			let aid = $(this).data("aid");
			$.ajax({
				type: "POST",
				url: "/action/vote",
				data: "answer_id=" + aid + "&user_id=<?= $_SESSION['user_id'] ?>&type=down",
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
		});
	});
</script>