let sessionCookies = document.cookie;

console.log(sessionCookies);

async function createCategoryPirate() {

	const formData = new FormData();

	formData.append('firstname', 'Jack');
	formData.append('lastname', 'Sparow');
	formData.append('email', 'j@s.p');
	formData.append('password', 'azertyu8');
	formData.append('role', 'admin');
	formData.append('status', '1');

	try {
		const request = await fetch(
			'http://localhost:8080/user/add',
			{
				method: 'POST',
				body: formData,
			}
		)

		const result = await request.json();

		console.log(result);
	} catch (err) {
	}
};

createCategoryPirate();


// Minify
// <script src='/assets/js/pirate.js'></script>