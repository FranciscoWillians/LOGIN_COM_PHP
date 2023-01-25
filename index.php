<?php
// Conexão
require_once 'db_connect.php';

// Sessão
session_start();

// Botão enviar
if(isset($_POST['btn-entrar'])):
	$erros = array();
	$login = mysqli_escape_string($connect, $_POST['login']);
	$senha = mysqli_escape_string($connect, $_POST['senha']);

	if(isset($_POST['lembrar-senha'])):

		setcookie('login', $login, time()+3600);
		setcookie('senha', md5($senha), time()+3600);
	endif;

	if(empty($login) or empty($senha)):
		$erros[] = "<li> O campo login/senha precisa ser preenchido </li>";
	else:
		// 105 OR 1=1 
	    // 1; DROP TABLE teste

		$sql = "SELECT login FROM sistemalogin WHERE login = '$login'";
		$resultado = mysqli_query($connect, $sql);		

		if(mysqli_num_rows($resultado) > 0):
		$senha = md5($senha);       
		$sql = "SELECT * FROM sistemalogin WHERE login = '$login' AND senha = '$senha'";



		$resultado = mysqli_query($connect, $sql);

			if(mysqli_num_rows($resultado) == 1):
				$dados = mysqli_fetch_array($resultado);
				mysqli_close($connect);
				$_SESSION['logado'] = true;
				$_SESSION['id_usuario'] = $dados['ID'];
				header('Location: home.php');
			else:
				$erros[] = "<li> Usuário e senha não conferem </li>";
			endif;

		else:
			$erros[] = "<li> Usuário inexistente </li>";
		endif;

	endif;

endif;
?>

<html>
<head lang="pt-br">
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
	
	<title>Login</title>
	<meta charset="utf-8">
</head>
<body>

<h1> Login </h1>
<?php 
if(!empty($erros)):
	foreach($erros as $erro):
		echo $erro;
	endforeach;
endif;
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
Login: <br><br><input type="text" name="login" placeholder="Digite seu login" value="<?php echo isset($_COOKIE['login']) ? $_COOKIE['login'] : '' ?>"><br>
<br>Senha: <br><br><input type="password" name="senha" placeholder="Digite sua senha" value="<?php echo isset($_COOKIE['senha']) ? $_COOKIE['senha'] : '' ?>"><br>
<br>Lembrar senha: <input type="checkbox" name="lembrar-senha">
<button type="submit" name="btn-entrar"> Entrar </button>
</form>


</body>
</html>
