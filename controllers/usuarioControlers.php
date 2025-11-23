<?php
// controllers/UsuarioController.php
require_once __DIR__ . '/../models/Usuario.php';
session_start();

$action = $_GET['action'] ?? '';

$usuarioModel = new Usuario();


function redirect_with(string $url, array $params = []) {
    if (!empty($params)) {
        $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($params);
    }
    header("Location: $url");
    exit;
}

/**
 * Sanitização de dados básicos
 */
function sanitize($str) {
    return trim(filter_var($str, FILTER_SANITIZE_STRING));
}


if ($action === 'register_client') {

    $nome  = sanitize($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$nome || !$email || !$senha) {
        redirect_with('../views/register.php', ['error' => 'Preencha todos os campos']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../views/register.php', ['error' => 'E-mail inválido']);
    }

    $res = $usuarioModel->create($nome, $email, $senha, 'cliente');

    if ($res['success']) {
        redirect_with('../views/login.php', ['success' => 'Cadastro realizado com sucesso!']);
    }

    redirect_with('../views/register.php', ['error' => $res['message'] ?? 'Erro ao salvar']);
}

/**
 * =============================
 *   REGISTRO DE ADMIN
 * =============================
 */
if ($action === 'register_admin') {

    $nome  = sanitize($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$nome || !$email || !$senha) {
        redirect_with('../admin/register.php', ['error' => 'Preencha todos os campos']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../admin/register.php', ['error' => 'E-mail inválido']);
    }

    $res = $usuarioModel->create($nome, $email, $senha, 'admin');

    if ($res['success']) {
        redirect_with('../admin/login_admin.php', ['success' => 'Administrador criado com sucesso!']);
    }

    redirect_with('../admin/register.php', ['error' => $res['message'] ?? 'Erro']);
}

/**
 * =============================
 *   LOGIN CLIENTE
 * =============================
 */
if ($action === 'login_client') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../views/login.php', ['error' => 'E-mail inválido']);
    }

    $user = $usuarioModel->findByEmail($email);

    if ($user && $user['tipo'] === 'cliente' && password_verify($senha, $user['senha'])) {

        $_SESSION['user'] = [
            'id'   => $user['id'],
            'nome' => $user['nome'],
            'tipo' => 'cliente'
        ];

        redirect_with('../views/agendar.php');
    }

    redirect_with('../views/login.php', ['error' => 'Credenciais inválidas']);
}

/**
 * =============================
 *   LOGIN ADMIN
 * =============================
 */
if ($action === 'login_admin') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../admin/login_admin.php', ['error' => 'E-mail inválido']);
    }

    $user = $usuarioModel->findByEmail($email);

    if ($user && $user['tipo'] === 'admin' && password_verify($senha, $user['senha'])) {

        $_SESSION['user'] = [
            'id'   => $user['id'],
            'nome' => $user['nome'],
            'tipo' => 'admin'
        ];

        redirect_with('../admin/painel.php');
    }

    redirect_with('../admin/login_admin.php', ['error' => 'Credenciais inválidas']);

}

/**
 * Ação desconhecida → volta ao início
 */
redirect_with('../index.php');
