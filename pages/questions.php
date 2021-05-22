<?php include 'shared/header.php'; ?>
<?php include 'shared/navbar.php'; ?>
<main class="section-center">
	<?php include 'shared/space-list.php'; ?>
	<article class="main-panel">
		<?php include 'shared/space-description.php'; ?>
		<!-- answers - questions -->
		<div class="selection">
			<div class="answers ">
				<a href="/answers/<?= $_GET['cat_id'] ?>">Answers</a>
			</div>
			<div class="question active">
				<a href="/questions/<?= $_GET['cat_id'] ?>">Questions</a>
			</div>
		</div>
		<?php
		$allQuestions = $db->query("SELECT q.question_id,q.question,sp.space_name FROM questions q INNER JOIN spaces sp ON sp.space_id = q.question_category WHERE q.question_category = '{$_GET['cat_id']}' ORDER BY q.question_date DESC", PDO::FETCH_ASSOC);
		if ($allQuestions->rowCount()) {
			foreach ($allQuestions as $question) {
				$answer_count = $db->query("SELECT COUNT(answer_id) AS total FROM answers WHERE question_id = '{$question['question_id']}'")->fetch(PDO::FETCH_ASSOC);
		?>
				<article class="question-post">
					<div class="identifier">
						<small id="type">Question added</small> -
						<small id="space-title"><?= $question['space_name'] ?></small>
					</div>
					<div class="user-question-title">
						<a href="/question-answers/<?= $question['question_id'] ?>" target="_blank"><?= $question['question'] ?></a>
					</div>
					<footer class="answer-cta">
						<div class="comment-container">
							<button id="comment"><i class="fas fa-pencil-alt"></i></i></button>
							<span id="comment-count"><?= $answer_count['total'] ? $answer_count['total'] : 0 ?> Answer</span>
						</div>
					</footer>
				</article>
				<div class="add-comment-field invisible">
					<img class="post-user-img" src="./images/profile-img2.jpg" alt="user-profile-img" />
					<input type="text" id="comment-text" name="comment-text" placeholder="Answer this question" />
					<button data-qid="<?= $question['question_id'] ?>" id="add-comment-btn" class="add-comment-btn">Send</button>
				</div>
		<?php
			}
		} else {
			echo '<article class="question-post">
			<div class="user-question-title">
						There is no question.
					</div>
			</article';
		}
		?>
	</article>
</main>

<?php include 'shared/footer.php'; ?>
<script>
	$(function() {
		$('#add-comment-btn').click(function(e) {
			let answer = $('#comment-text').val();
			let qid = $(this).data("qid");

			$.ajax({
				type: "POST",
				url: "/action/answer-question",
				data: "answer=" + answer + "&question_id=" + qid + "&category_id=<?= $_GET['cat_id'] ?>&user_id=<?= $_SESSION['user_id'] ?>",
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