<?php
session_start();

require_once __DIR__ . '/../models/Usuario.php';

$action = $_GET['action'] ?? '';

$usuarioModel = new Usuario();

function redirect_with(string $url, array $params = []): void
{
    if (!empty($params)) {
        $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($params);
    }
    header("Location: {$url}");
    exit;
}

function sanitize(string $str): string
{
    return trim(strip_tags($str));
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($action === 'register_client') {

    if ($method !== 'POST') {
        redirect_with('../views/register.php');
    }

    $nome  = sanitize($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        redirect_with('../views/register.php', ['error' => 'Preencha todos os campos']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../views/register.php', ['error' => 'E-mail inválido']);
    }

    $res = $usuarioModel->create($nome, $email, $senha, 'cliente');

    if (!empty($res['success'])) {
        redirect_with('../views/login.php', ['success' => 'Cadastro realizado com sucesso!']);
    }

    redirect_with('../views/register.php', ['error' => $res['message'] ?? 'Erro ao salvar']);
}

if ($action === 'register_admin') {

    if ($method !== 'POST') {
        redirect_with('../admin/register.php');
    }

    $nome  = sanitize($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        redirect_with('../admin/register.php', ['error' => 'Preencha todos os campos']);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../admin/register.php', ['error' => 'E-mail inválido']);
    }

    $res = $usuarioModel->create($nome, $email, $senha, 'admin');

    if (!empty($res['success'])) {
        redirect_with('../admin/login_admin.php', ['success' => 'Administrador criado com sucesso!']);
    }

    redirect_with('../admin/register.php', ['error' => $res['message'] ?? 'Erro']);
}

if ($action === 'login_client') {

    if ($method !== 'POST') {
        redirect_with('../views/login.php');
    }

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../views/login.php', ['error' => 'E-mail inválido']);
    }

    $user = $usuarioModel->findByEmail($email);

    if ($user && ($user['tipo'] ?? '') === 'cliente' && password_verify($senha, $user['senha'])) {

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'   => (int) $user['id'],
            'nome' => $user['nome'],
            'tipo' => 'cliente',
        ];

        redirect_with('../views/agendar.php');
    }

    redirect_with('../views/login.php', ['error' => 'Credenciais inválidas']);
}

if ($action === 'login_admin') {

    if ($method !== 'POST') {
        redirect_with('../admin/login_admin.php');
    }

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with('../admin/login_admin.php', ['error' => 'E-mail inválido']);
    }

    $user = $usuarioModel->findByEmail($email);

    if ($user && ($user['tipo'] ?? '') === 'admin' && password_verify($senha, $user['senha'])) {

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'   => (int) $user['id'],
            'nome' => $user['nome'],
            'tipo' => 'admin',
        ];

        redirect_with('../admin/painel.php');
    }

    redirect_with('../admin/login_admin.php', ['error' => 'Credenciais inválidas']);
}

redirect_with('../index.php');
