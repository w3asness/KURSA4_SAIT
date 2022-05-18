function register() {
	let email = $('#email').val()
	let log = $('#login').val()
	let pas = $('#passw').val()
	
	$.post('register.php', {email:email, login:log, password:pas}, function(data){
		let otvet = JSON.parse(data)
		if ('error' in otvet) {
			alert (otvet['error']['text'])
		}
		else if ('response' in otvet) {
			alert (otvet['response']['text'])
			window.open('login.html', '_blank');
		}
		else {
			alert('Непредвиденная ошибка')
			console.log(data)
		}
	});
}
	
function login() {
	let log = $('#login').val()
	let pas = $('#passw').val()
	
	$.get('users_get.php', {login:log, password:pas}, function(data){
		console.log(data)
		let otvet = JSON.parse(data)
		if ('error' in otvet) {
			alert(otvet['error']['text'])
		}
		else if ('response' in otvet) {
			if (otvet['response'].length == 1) {
				alert('Вы успешно авторизовались')
				user = otvet['response'][0]
				console.log(user)
				localStorage['login'] = user['login']
				localStorage['token'] = user['token']
				localStorage['expire'] = user['expiration']
				window.open('index.html', '_blank');
			}
			else {
				('Такого пользователя нет')
			}
		}
		else {
			alert('Непредвиденная ошибка')
			console.log(data)
		}
	});
}