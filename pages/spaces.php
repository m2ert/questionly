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


?>

<main class="spaces-center">
	<div class="current-spaces-container">
		<h2>Your spaces<br><small style="font-size:10px;">Last 5 spaces you created are listed.</small></h2>
		<button class="create-space-btn">Create a Space</button>
		<ul class="following-spaces">
			<?php
			$myspaces = $db->query("SELECT space_id,space_name,space_logo FROM spaces WHERE creator_id = '{$_SESSION['user_id']}' ORDER BY space_id DESC LIMIT 5", PDO::FETCH_ASSOC);
			if ($myspaces->rowCount()) {
				foreach ($myspaces as $myspace) {

			?>
					<li>
						<img class="mini-img" src="/images/<?= $myspace['space_logo'] ?>" alt="mini-space-banner" />
						<a href="/answers/<?= $myspace['space_id'] ?>"><?= $myspace['space_name'] ?></a>
					</li>
			<?php
				}
			}
			?>
		</ul>
	</div>
	<div class="discover-spaces-container">
		<h2>Discover Spaces</h2>
		<p>Spaces you might like</p>
		<div class="space-card-container">
			<?php
			$spaces = $db->query("SELECT space_id,space_name,space_logo FROM spaces WHERE space_status = '1'", PDO::FETCH_ASSOC);
			if ($spaces->rowCount()) {
				foreach ($spaces as $space) {

			?>
					<div class="single-card">
						<div class="card-bg">
							<img src="/images/login-bg.jpg" alt="space-bg-img" />
						</div>
						<div class="card-info">
							<h3 class="space-title"><?= $space['space_name'] ?></h3>
							<p class="space-desc">Everything about <?= $space['space_name'] ?>!</p>
						</div>

						<div class="card-logo">
							<img class="mini-img" src="/images/<?= $space['space_logo'] ?>" alt="mini-space-banner" />
						</div>
						<div class="card-follow">
							<a id="follow-space-btn" href="/answers/<?= $space['space_id'] ?>">
								<i class="fa fa-link fa-2x"></i>
							</a>
						</div>
					</div>
			<?php
				}
			}
			?>
		</div>
	</div>
</main>

<?php include 'shared/footer.php'; ?>
<script>
	$(document).ready(function() {
		$('.create-space-btn').click(function() {
			Swal.fire({
				title: 'Create a Space',
				input: 'text',
				inputAttributes: {
					autocapitalize: 'on'
				},
				showCancelButton: true,
				confirmButtonText: 'Create',
				inputValidator: (value) => {
					if (!value) {
						return 'You need to write something!'
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						type: "POST",
						url: "/action/create-space",
						data: "user_id=<?= $_SESSION['user_id'] ?>&space_name=" + result.value,
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