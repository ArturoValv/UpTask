<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                //Verificar que el usuario exista
                $auth = Usuario::where('email', $auth->email);

                if (!$auth || !$auth->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                } else {
                    //El usuario existe
                    if (password_verify($_POST['password'], $auth->password)) {
                        //Iniciar sesión
                        session_start();
                        $_SESSION['id'] = $auth->id;
                        $_SESSION['nombre'] = $auth->nombre;
                        $_SESSION['email'] = $auth->email;
                        $_SESSION['login'] = true;

                        //Redireccionar
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'Password incorrecto');
                    }
                }
            }
        }


        $alertas = Usuario::getAlertas();
        //Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            $existeUsuario = Usuario::where('email', $usuario->email);

            if (empty($alertas)) {
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El Usuario ya está registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear password
                    $usuario->hashPassword();

                    //Eliminar Password 2
                    unset($usuario->password2);

                    //Generar Token
                    $usuario->crearToken();

                    //Crear nuevo usuario
                    $resultado = $usuario->guardar();

                    //Enviar Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();


                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas,
        ]);
    }

    public static function olvide(Router $router)
    {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                //Buscar usuario
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado) {
                    //Generar nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    //Actualizar al 
                    $usuario->guardar();

                    //Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Imprimir Alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render(
            'auth/olvide',
            [
                'titulo' => 'Olvidé mi password',
                'alertas' => $alertas
            ]
        );
    }

    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostrar = true;

        if (!$token) header('Location: /');

        //Identificar usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Añadir nuevo password
            $usuario->sincronizar($_POST);

            //Validar Password
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                //Hashear nuevo password
                $usuario->hashPassword();

                //Eliminar token
                $usuario->token = null;

                //Guardar el usuario en la BD
                $resultado = $usuario->guardar();

                //Redireccionar
                if ($resultado) header(('Location: /'));
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render(
            'auth/reestablecer',
            [
                'titulo' => 'Reestablecer password',
                'alertas' => $alertas,
                'mostrar' => $mostrar
            ]
        );
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', ['titulo' => 'Cuenta creada exitosamente']);
    }

    public static function confirmar(Router $router)
    {

        $token = s($_GET['token']);

        if (!$token) header('Location: /');

        //Encontrar usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //Usuario con ese token no encontrado
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            //Confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            //Guardar en la BD
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', ['titulo' => 'Confirma tu cuenta UpTask', 'alertas' => $alertas]);
    }
}
