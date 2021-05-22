const comment = document.querySelector('#comment');

window.addEventListener('click', (e) => {
	if (e.target.classList.contains('fa-comment')) {
		const answerCard =
			e.target.parentElement.parentElement.parentElement.parentElement;

		const addCommentField = answerCard.nextElementSibling;
		addCommentField.classList.toggle('invisible');
		console.log(e.target);
	}
	if (e.target.classList.contains('fa-pencil-alt')) {
		const answerCard =
			e.target.parentElement.parentElement.parentElement.parentElement;

		const addCommentField = answerCard.nextElementSibling;
		addCommentField.classList.toggle('invisible');
		console.log(e.target);
	}
	// spaces.html

	// follow space
	if (e.target.classList.contains('fa-user-plus')) {
		e.target.classList.replace('fa-user-plus', 'fa-check');

		spaceFollowFunctionality();
	}
	if (e.target.classList.contains('fa-check')) {
		e.target.classList.replace('fa-check', 'fa-user-plus');
		spaceFollowFunctionality();
	}
});

function spaceFollowFunctionality() {
	const spaceCards = document.querySelectorAll('.single-card');
	spaceCards.forEach((card) => {
		if (
			card.lastElementChild.children[0].children[0].classList.contains(
				'fa-check'
			)
		) {
			document.querySelector('.following-spaces').insertAdjacentHTML(
				'beforeend',
				`<li href="#">
						<img
							class="mini-img"
							src="/images/mini-space.png"
							alt="mini-space-banner"
						/>
						<a href="#">${card.firstElementChild.nextElementSibling.firstElementChild.textContent}</a>
					</li>`
			);
		}
		// console.log(card.lastElementChild.children[0].children);

		// card.firstElementChild.nextElementSibling.firstElementChild.textContent
	});
}
