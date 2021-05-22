<?php
include 'shared/header.php';
include 'shared/navbar.php';
if (!$_GET['cat_id']) {
	header("Location: /spaces");
}
?>

<main class="section-center">
	<?php include 'shared/space-list.php'; ?>
	<article class="main-panel">
		<?php include 'shared/space-description.php'; ?>

		<!-- answers - questions -->
		<div class="selection">
			<div class="answers active">
				<a href="/answers/<?= $_GET['cat_id'] ?>">Answers</a>
			</div>
			<div class="question ">
				<a href="/questions/<?= $_GET['cat_id'] ?>">Questions</a>
			</div>
		</div>
		<?php
		$allAnswers = $db->query("SELECT a.answer,a.answer_date,qr.user_name,a.answer_id,a.question_id,a.answerer_id FROM answers a INNER JOIN question_register qr ON qr.user_id = a.answerer_id WHERE a.answer_category = '{$_GET['cat_id']}' ORDER BY a.answer_date DESC", PDO::FETCH_ASSOC);
		if ($allAnswers->rowCount()) {
			foreach ($allAnswers as $answer) {
				$vote_count = $db->query("SELECT COUNT(vote_id) AS total FROM answer_vote WHERE answer_id = '{$answer['answer_id']}'")->fetch(PDO::FETCH_ASSOC);
				$question_detail = $db->query("SELECT q.question_id,q.question,sp.space_name FROM questions q INNER JOIN spaces sp ON sp.space_id = q.question_category WHERE q.question_id='{$answer['question_id']}'")->fetch(PDO::FETCH_ASSOC);
				$user_profile = $db->query("SELECT user_img FROM user_profile WHERE user_id = '{$answer['answerer_id']}'")->fetch(PDO::FETCH_ASSOC);
				$comment_count = $db->query("SELECT COUNT(comment_id) AS total FROM answer_comment WHERE answer_id = '{$answer['answer_id']}'")->fetch(PDO::FETCH_ASSOC);

		?>
				<article class="answer-post">
					<div class="identifier">
						<small id="type">Answer</small> -
						<small id="space-title"><?= $question_detail['space_name'] ?></small>
					</div>
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
					<div class="user-question-title">
						<a href="/question-answers/<?= $answer['question_id'] ?>" target="_blank"><?= $question_detail['question']; ?></a>
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
					<input type="text" id="comment-text" placeholder="Add a comment">
					<button data-aid="<?= $answer['answer_id']; ?>" id="add-comment-btn" class="add-comment-btn">
						Send comment
					</button>
				</div>
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
	</article>
</main>

<?php include 'shared/footer.php'; ?>
<script>
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
</script>